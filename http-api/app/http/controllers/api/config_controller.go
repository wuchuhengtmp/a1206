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
	"gorm.io/gorm"
	"http-api/app/models/configs"
	"http-api/app/requests/api"
	"http-api/pkg/model"
	"http-api/pkg/response"
	"net/http"
)

type ConfigController struct {}

/**
 * 展示当前用户列表
 */
func (*ConfigController) ShowSms(w http.ResponseWriter, r *http.Request) {
	config := struct {
		ALIYUN_SMS_TEMPLATE          string
		ALIYUN_SMS_SIGN_NAME         string
		ALIYUN_SMS_ACCESS_KEY_SECRET string
		ALIYUN_SMS_ACCESS_KEY_ID     string
	}{}
	configModel := configs.Configs{}
	tmp := struct {
		Value string
	}{}
	model.DB.Model(&configModel).Where("name", "ALIYUN_SMS_TEMPLATE").First(&tmp)
	config.ALIYUN_SMS_TEMPLATE = tmp.Value
	model.DB.Model(&configModel).Where("name", "ALIYUN_SMS_SIGN_NAME").First(&tmp)
	config.ALIYUN_SMS_SIGN_NAME = tmp.Value
	model.DB.Model(&configModel).Where("name", "ALIYUN_SMS_ACCESS_KEY_SECRET").First(&tmp)
	config.ALIYUN_SMS_ACCESS_KEY_SECRET = tmp.Value
	model.DB.Model(&configModel).Where("name", "ALIYUN_SMS_ACCESS_KEY_ID").First(&tmp)
	config.ALIYUN_SMS_ACCESS_KEY_ID = tmp.Value
	res := response.Success{Data: config}
	res.ResponseByHttpWriter(w)
}

func (*ConfigController) UpdateSms(w http.ResponseWriter, r *http.Request) {
	decoder := json.NewDecoder(r.Body)
	var data api.UpdateSmsConfigRequest
	decoder.Decode(&data)
	errors := api.ValidateUpdateSmsConfigRequest(data)
	if len(errors) > 0 {
		res := response.Error{Errors: errors}
		res.ResponseByHttpWriter(w)
	} else {
		configModel := configs.Configs{}
		err := model.DB.Transaction(func(tx *gorm.DB) error {
			err := model.DB.Model(&configModel).Where("name", "ALIYUN_SMS_TEMPLATE").Update("value", data.ALIYUN_SMS_TEMPLATE).Error
			if err != nil { return err}
			err = model.DB.Model(&configModel).Where("name", "ALIYUN_SMS_SIGN_NAME").Update("value", data.ALIYUN_SMS_SIGN_NAME).Error
			if err != nil { return err}
			err = model.DB.Model(&configModel).Where("name", "ALIYUN_SMS_ACCESS_KEY_SECRET").Update("value", data.ALIYUN_SMS_ACCESS_KEY_SECRET).Error
			if err != nil { return err}
			err = model.DB.Model(&configModel).Where("name", "ALIYUN_SMS_ACCESS_KEY_ID").Update("value", data.ALIYUN_SMS_ACCESS_KEY_ID).Error
			if err != nil { return err}
			return nil
		})
		if err != nil {
			errMsg := err.Error()
			res := response.Error{
				Errors: map[string][]string{"database": {errMsg}},
			}
			res.ResponseByHttpWriter(w)
		} else {
			res := response.Success{}
			res.ResponseByHttpWriter(w)
		}
	}
}
