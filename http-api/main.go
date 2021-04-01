package main

import (
	_ "github.com/go-sql-driver/mysql"
	"github.com/gorilla/mux"
	"http-api/app/http/middlewares"
	"http-api/bootstrap"
	"http-api/config"
	"net/http"
)

var router = mux.NewRouter().StrictSlash(true)

func init()  {
	config.Initialize()
}

func main() {
	bootstrap.SetupDB()
	router = bootstrap.SetupRoute()
	http.ListenAndServe(":3000", middlewares.RemoveTrailingSlash(router))
}
