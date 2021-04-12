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

type AboutUpdateRequest struct {
	Content	string `valid:"content" json:"content"`
}


// 授权验证
func ValidateAboutUpdateRequest(data AboutUpdateRequest) map[string][]string {
	message := govalidator.MapData{
		"content": []string {
			"required:内容不能为空",
		},
	}
	rules := govalidator.MapData{
		"content": []string{"required"},
	}
	opts := govalidator.Options{
		Data: 			&data,
		Rules: 			rules,
		TagIdentifier:  "valid",
		Messages: 		message,
	}
	return govalidator.New(opts).ValidateStruct()
}