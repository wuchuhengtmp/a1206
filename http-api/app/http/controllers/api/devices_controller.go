/**
 * @Desc    The api is part of http-api
 * @Author  wuchuheng<wuchuheng@163.com>
 * @Blog    https://wuchuheng.com
 * @DATE    2021/4/9
 * @Listen  MIT
 */
package api

import (
	"http-api/app/models/devices"
	"http-api/pkg/response"
	"net/http"
	"strconv"
)

type DevicesController struct {}

/**
 * 展示设备列表
 */
func (*DevicesController) Show(w http.ResponseWriter, r *http.Request) {
	page, _ := strconv.Atoi(r.URL.Query().Get("page"))
	username := r.URL.Query().Get("username")
	status := r.URL.Query().Get("status")
	devicesModel := devices.Devices{}
	deviceListPage, _ := devicesModel.GetDevicesByPage(page, username, status)
	res := response.Success{
		Data: deviceListPage,
	}
	res.ResponseByHttpWriter(w)
}
