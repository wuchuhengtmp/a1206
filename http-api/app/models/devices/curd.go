/**
 * @Desc    The devices is part of http-api
 * @Author  wuchuheng<wuchuheng@163.com>
 * @Blog    https://wuchuheng.com
 * @DATE    2021/4/10
 * @Listen  MIT
 */
package devices

import (
	"fmt"
	"gorm.io/gorm"
	"http-api/app/models/users"
	"http-api/pkg/helper"
	"http-api/pkg/model"
	"time"
)

/**
 * 分页类型
 */
type PageInfoType struct {
	Devices []Devices `json:"devices"`
	Total   int64     `json:"total"`
}

/**
 * 获取设备分页
 */
func (*Devices) GetDevicesByPage(page int, username string, status string) (PageInfoType, error) {
	if page <= 0 {
		page = 1
	}
	var devicesList []Devices
	pageSize := 10
	offset := (page - 1) * pageSize
	getLoad := func() (*gorm.DB, error) {
		load := model.DB.Table("devices")
		// 账号过滤
		if username != "" {
			var user users.Users
			err := model.DB.Model(&user).Where("username LIKE ?", username+"%").First(&user).Error
			if err == nil {
				load = load.Where("user_id", user.ID)
			} else {
				return nil, fmt.Errorf("user not found")
			}
		}
		// 状态过滤
		if status != "" && status != "all" {
			load = load.Where("status", status)
		}
		return load, nil
	}
	if _, err := getLoad(); err != nil {
		return PageInfoType{}, nil
	}
	load, _ := getLoad()
	load.Offset(offset).Limit(pageSize).Find(&devicesList)

	pageInfo := PageInfoType{
		Devices: devicesList,
	}
	// 添加设备属性->分类名
	for i, c := range devicesList {
		var category struct {
			Name string `json:"name"`
		}
		model.DB.Table("categories").Where("id", c.CategoryId).First(&category)
		devicesList[i].CategoryName = category.Name
	}
	load, _ = getLoad()
	load.Count(&pageInfo.Total)
	return pageInfo, nil
}

/**
 * 当前在线设备
 */
func (d *Devices) GetTotalOnlineDevices() (total int64) {
	model.DB.Model(&Devices{}).Where("status", "online").Count(&total)
	return
}

/**
 * 全部设备
 */
func (d *Devices) GetTotalDevices() (total int64) {
	model.DB.Model(&Devices{}).Debug().Count(&total)
	return
}

/**
＊ 一周内连接并在线的用户设备用戶
*/
func (*Devices) GetDeviceOnlineForWeek() helper.SliceType {
	devices := helper.SliceType{0, 0, 0, 0, 0, 0, 0}
	t := time.Now().Unix()
	for i, _ := range devices {
		nt := time.Unix(t, 0)
		s := fmt.Sprintf("%d-%02d-%02d 00:00:00", nt.Year(), nt.Month(), nt.Day())
		e := fmt.Sprintf("%d-%02d-%02d 23:59:59", nt.Year(), nt.Month(), nt.Day())
		model.DB.Model(&Devices{}).
			Where("connected_at BETWEEN ? AND ?", s, e).
			Where("status", "online").
			Count(&devices[i])
		t = t - 60*60*24
	}
	return devices.ReversSlice()
}

/**
 * 一个月之内的设备
 */
func (*Devices) GetDeviceForMonth() helper.SliceType {
	var devices helper.SliceType
	for i := 0; i < 30; i++ {
		devices = append(devices, 0)
	}
	t := time.Now().Unix()
	for i, _ := range devices {
		t = t - 60*60*24
		nt := time.Unix(t, 0)
		s := fmt.Sprintf("%d-%02d-%02d 00:00:00", nt.Year(), nt.Month(), nt.Day())
		e := fmt.Sprintf("%d-%02d-%02d 23:59:59", nt.Year(), nt.Month(), nt.Day())
		model.DB.Model(&Devices{}).Where("created_at BETWEEN ? AND ?", s, e).Count(&devices[i])
	}
	return devices.ReversSlice()
}
