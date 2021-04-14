/**
 * 仪表盘
 */
package api

import (
	"http-api/app/models/devices"
	"http-api/app/models/users"
	"http-api/pkg/response"
	"net/http"
)
type DashboradController struct {}

func (*DashboradController) Show(w http.ResponseWriter, r *http.Request) {
	type groupType struct {
		Total int64   `json:"total"`
		List  []int64 `json:"list"`
	}
	var dashboard struct{
		User          groupType `json:"user"`          // 用户总数和月用户量
		UserForWeek   groupType `json:"userForWeek"`   // 周用户数用周添加量
		OnlineDevices groupType `json:"onlineDevices"` // 周在线设备和周总量`
		Devices       groupType `json:"devices"`       // 全部设备和月设备量
	}
	var users users.Users
	dashboard.User.Total = users.GetTotalUser()
	dashboard.UserForWeek.Total = users.GetTotalUserForWeek()
	var devicesModel devices.Devices
	dashboard.OnlineDevices.Total = devicesModel.GetTotalOnlineDevices()
	dashboard.User.List = users.GetUserForMonth()
	dashboard.Devices.Total = devicesModel.GetTotalDevices()
	dashboard.UserForWeek.List = users.GetUserForWeek()
	var device devices.Devices
	dashboard.OnlineDevices.List = device.GetDeviceOnlineForWeek()
	dashboard.Devices.List = device.GetDeviceForMonth()
	res := response.Success{
		Data: dashboard,
	}
	res.ResponseByHttpWriter(w)
}
