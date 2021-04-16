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
	// 展示设备列表
	rp.HandleFunc("/devices", middlewares.Auth((new (api.DevicesController)).Show)).Methods("GET").Name("devices.show")
	// 展示关于我们
	rp.HandleFunc("/about", middlewares.Auth((new (api.AboutController)).Show)).Methods("GET").Name("about.show")
	// 更新关于我们
	rp.HandleFunc("/about", middlewares.Auth((new (api.AboutController)).Update)).Methods("POST").Name("about.post")
	// 仪表盘
	rp.HandleFunc("/dashboard", middlewares.Auth((new (api.DashboradController)).Show)).Methods("GET").Name("dashboard.get")
	// 修改密码
	rp.HandleFunc("/users/{id:[0-9]+}/password", middlewares.Auth((new (api.UsersController)).UpdatePssword)).Methods("PUT").Name("users.update")
	// 获取sms配置
	rp.HandleFunc("/configs/sms", middlewares.Auth((new (api.ConfigController)).ShowSms)).Methods("GET").Name("config.sms.Show")
	// 修改sms配置
	rp.HandleFunc("/configs/sms", middlewares.Auth((new (api.ConfigController)).UpdateSms)).Methods("PUT").Name("config.sms.update")
	// 获取qiniu配置
	rp.HandleFunc("/configs/qiniu", middlewares.Auth((new (api.ConfigController)).ShowQiniu)).Methods("GET").Name("config.qiniu.Show")
	// 修改qiniu配置
	rp.HandleFunc("/configs/qiniu", middlewares.Auth((new (api.ConfigController)).UpdateQiniu)).Methods("PUT").Name("config.qiniu.update")
}
