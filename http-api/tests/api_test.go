/**
 * @Desc    The tests is part of http-api
 * @Author  wuchuheng<wuchuheng@163.com>
 * @Blog    https://wuchuheng.com
 * @DATE    2021/4/2
 * @Listen  MIT
 */
package tests

import (
	"encoding/json"
	"github.com/stretchr/testify/assert"
	"io/ioutil"
	"net/http"
	"net/url"
	"strconv"
	"testing"
)


func TestAllApi(t *testing.T)  {
	baseURL = baseURL + "/api"
	type postDataType map[string][]string

	var tests = []struct {
		method 	string
		url 	string
		expected int
		data url.Values
	} {
		{"POST", "/authorizations", 200,url.Values{"username": {"admin"}, "password": {"password"}}},
	}
	for _, test := range tests {
		t.Logf("Currently requested link: %v \n", test.url)
		var (
			resp *http.Response
			err error
		)
		switch {
		case test.method == "POST":
			resp, err = http.PostForm(baseURL + test.url, test.data)
		default:
			resp, err = http.Get(baseURL + test.url)
		}
		assert.NoError(t, err, "It was error to request " + test.url)
		body, _ := ioutil.ReadAll(resp.Body)
		var resData struct{
			IsSuccess bool 		  `json:"isSuccess"`
		}
		json.Unmarshal(body, &resData)
		assert.Equal(t, resData.IsSuccess, true,
			test.url +
				" method: " + test.method +
				"  status code " +
				strconv.Itoa(test.expected) +
				" must be returned")
	}
}
