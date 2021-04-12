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
	"http-api/app/models/configs"
	"http-api/app/requests/api"
	"http-api/pkg/response"
	"net/http"
)

type AboutController struct {}

/**
 * 展示关于我们
 */
func (*AboutController) Show(w http.ResponseWriter, r *http.Request) {
	var config configs.Configs
	var data struct {
		Value string `json:"value"`
	}
	about,_ := config.GetAbout()
	data.Value = about
	res := response.Success{
		Data: data,
	}
	res.ResponseByHttpWriter(w)
}

/**
 * 更新关于我们
 */
func (*AboutController) Update(w http.ResponseWriter, r *http.Request)  {
	decoder := json.NewDecoder(r.Body)
	var data api.AboutUpdateRequest
	decoder.Decode(&data)
	errors := api.ValidateAboutUpdateRequest(data)
	if len(errors) > 0 {
		err := response.Error{
			Errors: errors,
		}
		err.ResponseByHttpWriter(w)
	} else {
		var configModel configs.Configs
		err :=configModel.Update(data.Content)
		if err != nil {
			errors := response.Error{
				Errors: map[string][]string{
					"content": {"保存失败"},
				},
			}
			errors.ResponseByHttpWriter(w)
		} else {
			res := response.Success{
				Data: data,
			}
			res.ResponseByHttpWriter(w)
		}
	}
}
