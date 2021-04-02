/**
 * @Desc    HTTP api integration testing
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

func TestAllApi(t *testing.T) {
	baseURL = baseURL + "/api"
	var tests = []struct {
		method            string
		url               string
		expected          int
		data              url.Values
		expectedIsSuccess bool
	}{
		{"POST", "/authorizations", 200, url.Values{"username": {"admin"}, "password": {"password"}}, true},
		{"POST", "/authorizations", 200, url.Values{"username": {"admin"}, "password1": {"password"}}, false},
	}
	for _, test := range tests {
		t.Logf("Currently requested link: %v \n", test.url)
		var (
			resp *http.Response
			err  error
		)
		switch {
		case test.method == "POST":
			resp, err = http.PostForm(baseURL+test.url, test.data)
		default:
			resp, err = http.Get(baseURL + test.url)
		}
		assert.NoError(t, err, "It was error to request "+test.url)
		body, _ := ioutil.ReadAll(resp.Body)
		var resData struct {
			IsSuccess bool `json:"isSuccess"`
		}
		json.Unmarshal(body, &resData)
		assert.Equal(t, resData.IsSuccess, test.expectedIsSuccess,
			test.url+
				" method: "+test.method+
				" status code "+
				strconv.Itoa(test.expected)+
				" expect: "+strconv.FormatBool(test.expectedIsSuccess)+"\n"+
				" result: "+strconv.FormatBool(resData.IsSuccess))
	}
}
