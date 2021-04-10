/**
 * @Desc    The users is part of http-api
 * @Author  wuchuheng<wuchuheng@163.com>
 * @Blog    https://wuchuheng.com
 * @DATE    2021/4/1
 * @Listen  MIT
 */
package users

import (
	"gorm.io/gorm"
)

type Users struct {
	gorm.Model
	ID 			int64 	`json:"id"`
	Password 	string
	Username 	string 	`json:"username"`
	Avatar 		string  `json:"avatar"`
	CreatedAt 	string  `json:"created_at"`
	Nickname 	string  `json:"nickname"`
}
