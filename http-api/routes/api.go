package routes

/*
 * api 路由
 *
 */

import (
	"github.com/gorilla/mux"
	"http-api/app/http/controllers/api"
)

func RegisterApiRoutes(r *mux.Router) {
	rp := r.PathPrefix("/api").Subrouter()
	a := new (api.AuthorizationController)
	rp.HandleFunc("/authorizations", a.Create).Methods("POST").Name("authorization.create")
}
