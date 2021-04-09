/**
 * @Desc    The response is part of http-api
 * @Author  wuchuheng<wuchuheng@163.com>
 * @Blog    https://wuchuheng.com
 * @DATE    2021/4/1
 * @Listen  MIT
 */
package response

import (
	"encoding/json"
	"fmt"
	"net/http"
)

type Error struct {
	Errors map[string][]string
	ErrorCode int
}

var ErrorCodes = struct {
	LoginFail int
}{
	LoginFail: 50004, // 登录失败
}

func (e *Error) ResponseByHttpWriter(w http.ResponseWriter)  {
	w.Header().Set("Content-Type", "application/json; charset=utf-8")
	var res struct{
		IsSuccess bool `json:"isSuccess"`
		ErrorCode int  `json:"errorCode"`
		ErrorMsg interface{} `json:"errorMsg"`
	}
	res.ErrorMsg = e.Errors
	res.ErrorCode = e.ErrorCode
	repStr, _ := json.Marshal(res)
	fmt.Fprint(w, string(repStr))
}

type Success struct {
	IsSuccess 	bool 		`json:"isSuccess"`
	Data 		interface{} `json:"data"`
}

func (e *Success) ResponseByHttpWriter(w http.ResponseWriter)  {
	e.IsSuccess = true
	w.Header().Set("Content-Type", "application/json; charset=utf-8")
	repStr, _ := json.Marshal(e)
	fmt.Fprint(w, string(repStr))
}
