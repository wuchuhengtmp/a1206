/**
 * @Desc    The api is part of http-api
 * @Author  wuchuheng<wuchuheng@163.com>
 * @Blog    https://wuchuheng.com
 * @DATE    2021/4/1
 * @Listen  MIT
 */
package api

import (
	"encoding/json"
	"fmt"
	"github.com/thedevsaddam/govalidator"
	"http-api/app/models/users"
	"strings"
)

type AuthorizationCreateRequest struct {
	Password	string `valid:"password"`
	Username	string `valid:"username"`
}

func init()  {
	// 验证账号密码
	govalidator.AddCustomRule("check_account", func(field string, rule string, message string, value interface{}) error {
		accountJson := strings.TrimPrefix(rule, "check_account:")
		var account AuthorizationCreateRequest
		json.Unmarshal([]byte(accountJson), &account)

		user := users.User{
			Password: account.Password,
			Username: account.Username,
		}
		if len(account.Password) == 0 || len(account.Username) == 0 {
			return nil
		}
		if !user.IsUser() {
			return fmt.Errorf("账号或密码不正确")
		}
		return nil
	})
}

// 授权验证
func ValidateAuthorizationCreateRequest(data AuthorizationCreateRequest) map[string][]string {
	message := govalidator.MapData{
		"username": []string {
			"required:用户名不能为空",
		},
		"password": []string {
			"required:密码不能为空",
		},
	}
	account, _ := json.Marshal(data)
	rules := govalidator.MapData{
		"username": []string{"required"},
		"password": []string{"required", fmt.Sprintf("check_account:%s", string(account))},
	}
	opts := govalidator.Options{
		Data: 			&data,
		Rules: 			rules,
		TagIdentifier:  "valid",
		Messages: 		message,
	}
	return govalidator.New(opts).ValidateStruct()
}