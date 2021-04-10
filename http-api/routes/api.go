package routes

/*
 * api 路由
 *
 */

import (
	"github.com/gorilla/mux"
	"http-api/app/http/controllers/api"
	"http-api/app/http/middlewares"
)

func RegisterApiRoutes(r *mux.Router) {
	rp := r.PathPrefix("/api").Subrouter()
	a := new (api.AuthorizationController)
	// 获取token
	rp.HandleFunc("/authorizations", a.Create).Methods("POST").Name("authorization.create")
	// 获取当前用户信息
	rp.HandleFunc("/me", middlewares.Auth( (new (api.MeController)).Show)).Methods("GET").Name("me.show")
	// 展示用户列表
	rp.HandleFunc("/users", middlewares.Auth((new (api.UsersController)).Show)).Methods("GET").Name("users.show")
}
