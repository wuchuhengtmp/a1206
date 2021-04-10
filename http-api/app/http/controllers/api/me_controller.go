/**
 * @Desc    The api is part of http-api
 * @Author  wuchuheng<wuchuheng@163.com>
 * @Blog    https://wuchuheng.com
 * @DATE    2021/4/9
 * @Listen  MIT
 */
package api

import (
	"http-api/pkg/response"
	"net/http"
)

type MeController struct {}

/**
 * 展示当前用户信息
 */
func (*MeController) Show(w http.ResponseWriter, r *http.Request) {
	var meInfo struct {
		Id           int      `json:"id"`
		Username     string   `json:"username"`
		Name         string   `json:"name"`
		Avatar       string   `json:"avatar"`
		Introduction string   `json:"introduction"`
		Email        string   `json:"email"`
		Phone        string   `json:"phone"`
		Roles        []string `json:"roles"`

	}
	meInfo.Id = 1
	meInfo.Username = "admin"
	meInfo.Name = "Super Admin"
	meInfo.Avatar = "https://wpimg.wallstcn.com/f778738c-e4f8-4870-b634-56703b4acafe.gif"
	meInfo.Introduction = "I am a super administrator"
	meInfo.Email = "admin@test.com"
	meInfo.Phone = "1234567890"
	meInfo.Roles = []string{"admin"}
	res := response.Success{
		Data: meInfo,
	}
	res.ResponseByHttpWriter(w)
}
