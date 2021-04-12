/**
 * @Desc    The configs is part of http-api
 * @Author  wuchuheng<wuchuheng@163.com>
 * @Blog    https://wuchuheng.com
 * @DATE    2021/4/12
 * @Listen  MIT
 */
package configs

import (
	"gorm.io/gorm"
	"http-api/pkg/model"
)

type Configs struct {
	gorm.Model
	ID    int64  `json:"id"`
	Name  string `json:"name"`
	Value string `json:"value"`
}

var AboutKey = "about"

func (*Configs) GetAbout() (string, error) {
	var about Configs
	model.DB.Model(&Configs{}).Where("name", AboutKey).First(&about)
	return about.Value, nil
}

/**
 * 更新关于我们
 */
func (*Configs) Update(content string) error {
	return model.DB.Model(&Configs{}).Where("name", AboutKey).Update("value", content).Error
}
