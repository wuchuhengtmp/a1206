/**
 * @Desc    The users is part of http-api
 * @Author  wuchuheng<wuchuheng@163.com>
 * @Blog    https://wuchuheng.com
 * @DATE    2021/4/2
 * @Listen  MIT
 */
package users

import (
	"http-api/pkg/encrypt"
	"http-api/pkg/model"
	"http-api/pkg/types"
)

// 有没有这个用户
func (u *Users) IsUser() bool {
	err := model.
		DB.
		First(u, "username = ? AND password = ?", u.Username, encrypt.Hash(u.Password)).
		Error
	return err == nil
}

func (u *Users) GetUser() error {
	err := model.
		DB.
		First(u, "username = ? AND password = ?", u.Username, encrypt.Hash(u.Password)).
		Error
	return err;
}
type UserListPageType []struct {
	ID          int64          `json:"id"`
	Username    string         `json:"username"`
	Avatar      string         `json:"avatar"`
	CreatedAt   types.JsonTime `json:"createdAt"`
	Nickname    string         `json:"nickname"`
	TotalDevice int64          `json:"totalDevice"`
}

type PageInfoType struct {
	Users	UserListPageType `json:"users"`
	Total	int64			 `json:"total"`
}
func (u *Users) GetUsersByPage(page int) (PageInfoType, error) {
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
	// 添加用户属性->设备量
	for i, user := range userList {
		var totalDevice  int64
		model.DB.Table("devices").Where("user_id", user.ID).Count(&totalDevice)
		userList[i].TotalDevice = totalDevice
	}
	model.DB.Table("users").Count(&pageInfo.Total)
	return pageInfo, nil
}
