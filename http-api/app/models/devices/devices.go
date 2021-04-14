/**
 * @Desc    The devices is part of http-api
 * @Author  wuchuheng<wuchuheng@163.com>
 * @Blog    https://wuchuheng.com
 * @DATE    2021/4/10
 * @Listen  MIT
 */
package devices

import (
	"gorm.io/gorm"
	"http-api/pkg/types"
)

type Devices struct {
	gorm.Model
	Id           int            `json:"id"`
	DeviceId     string         `json:"deviceId"`  // 设备ID
	IpAddress    string         `json:"ipAddress"` // 设备ip
	Status       string         `json:"status"`
	PlayState    int            `json:"playState"`
	PlayMode     int            `json:"playMode"`
	PlaySound    int            `json:"playSound"`    // 音量
	Alias        string         `json:"alias"`        //  别名
	CategoryName string         `json:"categoryName"` // 分类名
	CategoryId   int64          `json:"categoryId"`   // 分类名
	FileCnt      int            `json:"fileCnt"`      // 总曲目数
	FileCurrent  string         `json:"fileCurrent"`  // 当前曲目
	MemorySize   int            `json:"memorySize"`   // 内存余量
	CreatedAt    types.JsonTime `json:"createdAt"`    // 创建时间
}