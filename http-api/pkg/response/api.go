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

type Errors map[string][]string

func (e *Errors) ResponseByHttpWriter(w http.ResponseWriter)  {
	w.Header().Set("Content-Type", "application/json; charset=utf-8")
	var res struct{
		IsSuccess bool
		ErrorCode int
		ErrorMessage interface{}
	}
	res.ErrorMessage = &e
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
