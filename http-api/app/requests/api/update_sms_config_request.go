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

type UpdateSmsConfigRequest struct {
	ALIYUN_SMS_TEMPLATE          string `valid:"ALIYUN_SMS_TEMPLATE"`
	ALIYUN_SMS_SIGN_NAME         string `valid:"ALIYUN_SMS_SIGN_NAME"`
	ALIYUN_SMS_ACCESS_KEY_SECRET string `valid:"ALIYUN_SMS_ACCESS_KEY_SECRET"`
	ALIYUN_SMS_ACCESS_KEY_ID     string `valid:"ALIYUN_SMS_ACCESS_KEY_ID"`
}

// 密码验证
func ValidateUpdateSmsConfigRequest(data UpdateSmsConfigRequest) map[string][]string {
	message := govalidator.MapData{
		"ALIYUN_SMS_TEMPLATE":          []string{"required: ALIYUN_SMS_TEMPLATE 不能为空"},
		"ALIYUN_SMS_SIGN_NAME":         []string{"required: ALIYUN_SMS_SIGN_NAME 不能为空"},
		"ALIYUN_SMS_ACCESS_KEY_SECRET": []string{"required: ALIYUN_SMS_ACCESS_KEY_SECRET 不能为空"},
		"ALIYUN_SMS_ACCESS_KEY_ID":     []string{"required: ALIYUN_SMS_ACCESS_KEY_ID 不能为空"},
	}
	rules := govalidator.MapData{
		"ALIYUN_SMS_TEMPLATE":          []string{"required"},
		"ALIYUN_SMS_SIGN_NAME":         []string{"required"},
		"ALIYUN_SMS_ACCESS_KEY_SECRET": []string{"required"},
		"ALIYUN_SMS_ACCESS_KEY_ID":     []string{"required"},
	}
	opts := govalidator.Options{
		Data: 			&data,
		Rules: 			rules,
		TagIdentifier:  "valid",
		Messages: 		message,
	}
	return govalidator.New(opts).ValidateStruct()
}