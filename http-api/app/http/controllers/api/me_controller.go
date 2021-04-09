/**
 * @Desc    The api is part of http-api
 * @Author  wuchuheng<wuchuheng@163.com>
 * @Blog    https://wuchuheng.com
 * @DATE    2021/4/9
 * @Listen  MIT
 */
package api

import (
	"fmt"
	"net/http"
)

type MeController struct {}

/**
 * 展示当前用户信息
 */
func (*MeController) Show(w http.ResponseWriter, r *http.Request) {
	fmt.Print("hello\n")
}
