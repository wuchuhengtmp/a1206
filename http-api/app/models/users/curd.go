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
)

// 有没有这个用户
func (u *User) IsUser() bool {
	err := model.
		DB.
		First(u, "username = ? AND password = ?", u.Username, encrypt.Hash(u.Password)).
		Error
	return err == nil
}

func (u *User) GetUser() error {
	err := model.
		DB.
		First(u, "username = ? AND password = ?", u.Username, encrypt.Hash(u.Password)).
		Error
	return err;
}
