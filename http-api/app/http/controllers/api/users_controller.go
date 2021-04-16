/**
 * @Desc    The api is part of http-api
 * @Author  wuchuheng<wuchuheng@163.com>
 * @Blog    https://wuchuheng.com
 * @DATE    2021/4/9
 * @Listen  MIT
 */
package api

import (
	"encoding/json"
	"http-api/app/models/users"
	"http-api/app/requests/api"
	"http-api/pkg/encrypt"
	"http-api/pkg/logger"
	"http-api/pkg/model"
	"http-api/pkg/response"
	"http-api/pkg/route"
	"net/http"
	"strconv"
)

type UsersController struct {}

/**
 * 展示当前用户列表
 */
func (*UsersController) Show(w http.ResponseWriter, r *http.Request) {
	page, _ := strconv.Atoi(r.URL.Query().Get("page"))
	userModel := users.Users{}
	userList, _ := userModel.GetUsersByPage(page)
	res := response.Success{
		Data: userList,
	}
	res.ResponseByHttpWriter(w)
}

/**
 * 修改用户密码
 */
func (*UsersController) UpdatePssword(w http.ResponseWriter, r *http.Request) {
	var userInfo api.UserUpdatePasswordRequest
	decoder := json.NewDecoder(r.Body)
	decoder.Decode(&userInfo)
	errs := api.ValidateUserUpdatePasswordRequest(userInfo)
	id := route.GetRouteVariable("id", r)
	isUser := func(uid string) bool {
		User := users.Users{}
		err := model.DB.Model(&User).Where("id", uid).First(&User).Error
		if err != nil {
			return false
		} else {
			return true
		}
	}
	if len(errs) > 0  {
		e := response.Error{ Errors: errs }
		e.ResponseByHttpWriter(w)
	} else if id == "" {
		e := response.Error{ Errors: map[string][]string {"id": {"用户id不能为空"}} }
		e.ResponseByHttpWriter(w)
	} else if isUser(id) == false {

		e := response.Error{Errors: map[string][]string{"id": {"没有这个用户"}}}
		e.ResponseByHttpWriter(w)
	} else {
		user := users.Users{}
		password := encrypt.Hash(userInfo.Password)
		err := model.DB.Model(&user).Where("id", id).Update("password", password).Error
		logger.LogError(err)
		if err != nil {
			e := response.Error{Errors: map[string][]string{"password": {"操作失败"}}}
			e.ResponseByHttpWriter(w)
		} else {
			e := response.Success{}
			e.ResponseByHttpWriter(w)
		}
	}
}
