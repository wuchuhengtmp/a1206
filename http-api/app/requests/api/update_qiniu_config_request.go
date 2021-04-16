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

type UpdateQiniuConfigRequest struct {
	QINIU_ACCESSKEY string `valid:"QINIU_ACCESSKEY"`
	QINIU_SECRETKEY string `valid:"QINIU_SECRETKEY"`
	QINIU_BUCKET    string `valid:"QINIU_BUCKET"`
	QINIU_DOMAIN    string `valid:"QINIU_DOMAIN"`
}

// 密码验证
func ValidateUpdateQiniuConfigRequest(data UpdateQiniuConfigRequest) map[string][]string {
	message := govalidator.MapData{
		"QINIU_ACCESSKEY": []string{"required: QINIU_ACCESSKEY 不能为空"},
		"QINIU_SECRETKEY": []string{"required: QINIU_SECRETKEY 不能为空"},
		"QINIU_BUCKET":    []string{"required: QINIU_BUCKET 不能为空"},
		"QINIU_DOMAIN":    []string{"required: QINIU_DOMAIN 不能为空"},
	}
	rules := govalidator.MapData{
		"QINIU_ACCESSKEY": []string{"required"},
		"QINIU_SECRETKEY": []string{"required"},
		"QINIU_BUCKET":    []string{"required"},
		"QINIU_DOMAIN":    []string{"required"},
	}
	opts := govalidator.Options{
		Data: 			&data,
		Rules: 			rules,
		TagIdentifier:  "valid",
		Messages: 		message,
	}
	return govalidator.New(opts).ValidateStruct()
}