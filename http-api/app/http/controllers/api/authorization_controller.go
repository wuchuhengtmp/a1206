package api

import (
	"encoding/json"
	"http-api/app/models/users"
	"http-api/app/requests/api"
	"http-api/pkg/jwt"
	"http-api/pkg/response"
	"net/http"
)
type AuthorizationController struct {}

// 生成token
func (*AuthorizationController) Create(w http.ResponseWriter, r *http.Request) {
	decoder := json.NewDecoder(r.Body)
	var account api.AuthorizationCreateRequest
	decoder.Decode(&account)
	errors := api.ValidateAuthorizationCreateRequest(account)
	if len(errors) > 0 {
		var errRes response.Errors = errors
		errRes.ResponseByHttpWriter(w)
	} else {
		var user = users.User{
			Username: r.PostFormValue("username"),
			Password: r.PostFormValue("password"),
		}
		user.GetUser()
		jwtToken,_ := jwt.GenerateTokenByUID(user.ID)
		var resData struct { AccessToken string 	`json:"accessToken"` }
		resData.AccessToken = jwtToken
		res := response.Success{
			Data: resData,
		}
		res.ResponseByHttpWriter(w)
	}
}
