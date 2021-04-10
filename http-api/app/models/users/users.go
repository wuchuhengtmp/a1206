/**
 * @Desc    The users is part of http-api
 * @Author  wuchuheng<wuchuheng@163.com>
 * @Blog    https://wuchuheng.com
 * @DATE    2021/4/1
 * @Listen  MIT
 */
package users

import (
	"fmt"
	"http-api/pkg/model"
	"time"
)

type User struct {
	ID 			int64 	`json:"id"`
	Password 	string
	Username 	string 	`json:"username"`
	Avatar 		string  `json:"avatar"`
	CreatedAt 	string  `json:"created_at"`
	Nickname 	string  `json:"nickname"`
}


type JsonTime time.Time
func (this JsonTime) MarshalJSON() ([]byte, error) {
	var stamp = fmt.Sprintf("\"%s\"", time.Time(this).Format("2006-01-02 15:04:05"))
	return []byte(stamp), nil
}

type UserListPageType []struct {
	ID        int64    `json:"id"`
	Username  string   `json:"username"`
	Avatar    string   `json:"avatar"`
	CreatedAt JsonTime `json:"createdAt"`
	Nickname  string   `json:"nickname"`
}

type PageInfoType struct {
	Users	UserListPageType `json:"users"`
	Total	int64			 `json:"total"`
}
func (u *User) GetUsersByPage(page int) (PageInfoType, error) {
	if page <= 0 {
		page = 1
	}
	var userList UserListPageType
	pageSize := 10
	offset := (page - 1) * pageSize
	model.DB.Table("users").Offset(offset).Limit(pageSize).Find(&userList)
	pageInfo :=  PageInfoType{
		Users: userList,
	}
	model.DB.Table("users").Count(&pageInfo.Total)
	return pageInfo, nil
}
