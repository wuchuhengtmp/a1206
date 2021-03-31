package bootstrap

import (
	"github.com/gorilla/mux"
	"http-api/pkg/model"
	"http-api/routes"
	"time"
)

func SetupRoute() *mux.Router  {
	router := mux.NewRouter()
	routes.RegisterWebRoutes(router)
	return router
}

func SetupDB() {
	db := model.ConnectDB()
	sqlDB, _ := db.DB()

	// 设置最大连接
	sqlDB.SetMaxOpenConns(100)
	// 设置最大空闲连接
	sqlDB.SetMaxIdleConns(25)
	// 设置每个链接的过期时间
	sqlDB.SetConnMaxLifetime(5 * time.Minute)
}