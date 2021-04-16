/**
 * @Desc    The api is part of http-api
 * @Author  wuchuheng<wuchuheng@163.com>
 * @Blog    https://wuchuheng.com
 * @DATE    2021/4/1
 * @Listen  MIT
 */
package api

import (
	"github.com/thedevsaddam/govalidator"
)

type UserUpdatePasswordRequest struct {
	Password	string `valid:"password"`
}

// 密码验证
func ValidateUserUpdatePasswordRequest(data UserUpdatePasswordRequest) map[string][]string {
	message := govalidator.MapData{
		"password": []string {
			"required:密码不能为空",
		},
	}
	rules := govalidator.MapData{
		"password": []string{"required"},
	}
	opts := govalidator.Options{
		Data: 			&data,
		Rules: 			rules,
		TagIdentifier:  "valid",
		Messages: 		message,
	}
	return govalidator.New(opts).ValidateStruct()
}