package bootstrap

import (
	"github.com/gorilla/mux"
	"http-api/routes"
)

func SetupRoute() *mux.Router  {
	router := mux.NewRouter()
	routes.RegisterWebRoutes(router)
	return router
}