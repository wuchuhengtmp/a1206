/**
 * @Desc    The devices is part of http-api
 * @Author  wuchuheng<wuchuheng@163.com>
 * @Blog    https://wuchuheng.com
 * @DATE    2021/4/10
 * @Listen  MIT
 */
package devices

import "http-api/pkg/model"

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
func (*Devices) GetDevicesByPage(page int) (PageInfoType, error) {
	if page <= 0 {
		page = 1
	}
	var devicesList []Devices
	pageSize := 10
	offset := (page - 1) * pageSize
	model.DB.Table("devices").Offset(offset).Limit(pageSize).Find(&devicesList)
	pageInfo :=  PageInfoType{
		Devices: devicesList,
	}
	// 添加设备属性->分类名
	for i, c := range devicesList{
		var category struct{
			Name string `json:"name"`
		}
		model.DB.Table("categories").Where("id", c.CategoryId).First(&category)
		devicesList[i].CategoryName = category.Name
	}
	model.DB.Table("devices").Count(&pageInfo.Total)
	return pageInfo, nil
}
