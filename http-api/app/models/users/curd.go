/**
 * @Desc    The users is part of http-api
 * @Author  wuchuheng<wuchuheng@163.com>
 * @Blog    https://wuchuheng.com
 * @DATE    2021/4/2
 * @Listen  MIT
 */
package users

import (
	"fmt"
	"http-api/pkg/encrypt"
	"http-api/pkg/model"
	"http-api/pkg/types"
	"time"
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

/**
 * 用户总数
 */
func (u *Users) GetTotalUser () (total int64) {
	model.DB.Model(&Users{}).Count(&total)
	return total
}

/*
 * 周用户总数
*/
func (u *Users) GetTotalUserForWeek () (total int64) {
	now := time.Now().Format(time.RFC3339)
	lastWeek := time.Unix( time.Now().Unix() - 60 * 60 * 24 * 7, 0).Format(time.RFC3339)
	model.DB.Model(&Users{}).Where("created_at BETWEEN ? AND ?", lastWeek, now).Count(&total)
	return total
}

type UserTotalListType []int64

/**
 * 翻转slice
 */
func (u *UserTotalListType) ReversSlice() UserTotalListType {
	s := *u
	for i, j := 0, len(s)-1; i < j; i, j = i+1, j-1 {
		s[i], s[j] = s[j], s[i]
	}
	return s
}

/*
 * 周用户列表
 */
func (u *Users) GetUserForWeek () UserTotalListType {
	userList := UserTotalListType{0, 0, 0, 0, 0, 0, 0}
	 t := time.Now().Unix()
	for i, _ := range userList {
		t = t - 60 * 60 * 24
		nt := time.Unix(t, 0)
		s := fmt.Sprintf("%d-%02d-%02d 00:00:00",  nt.Year(), nt.Month(), nt.Day())
		e := fmt.Sprintf("%d-%02d-%02d 23:59:59",  nt.Year(), nt.Month(), nt.Day())
		model.DB.Model(&Users{}).Where("created_at BETWEEN ? AND ?", s, e).Count(&userList[i])
	}
	return userList.ReversSlice()
}

/**
  ＊ 月用戶
 */
func (u *Users) GetUserForMonth () UserTotalListType {
	userList := UserTotalListType{}
	for  i := 0; i < 30; i++ {
		userList = append(userList, 0)
	}
	t := time.Now().Unix()
	for i, _ := range userList {
		t = t - 60 * 60 * 24
		nt := time.Unix(t, 0)
		s := fmt.Sprintf("%d-%02d-%02d 00:00:00",  nt.Year(), nt.Month(), nt.Day())
		e := fmt.Sprintf("%d-%02d-%02d 23:59:59",  nt.Year(), nt.Month(), nt.Day())
		model.DB.Model(&Users{}).Where("created_at BETWEEN ? AND ?", s, e).Count(&userList[i])
	}
	return userList.ReversSlice()
}
