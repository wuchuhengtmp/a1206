/**
 * @Desc    The api is part of http-api
 * @Author  wuchuheng<wuchuheng@163.com>
 * @Blog    https://wuchuheng.com
 * @DATE    2021/4/9
 * @Listen  MIT
 */
package api

import (
	"http-api/app/models/users"
	"http-api/pkg/response"
	"net/http"
	"strconv"
)

type UsersController struct {}

/**
 * 展示当前用户列表
 */
func (*UsersController) Show(w http.ResponseWriter, r *http.Request) {
	page, _ := strconv.Atoi(r.URL.Query().Get("page"))
	userModel := users.User{}
	userList, _ := userModel.GetUsersByPage(page)
	res := response.Success{
		Data: userList,
	}
	res.ResponseByHttpWriter(w)
}
