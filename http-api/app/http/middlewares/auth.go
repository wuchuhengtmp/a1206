package middlewares

import (
	"fmt"
	"net/http"
)

func Auth(next HttpHandlerFunc) HttpHandlerFunc {
	return func(w http.ResponseWriter, r *http.Request) {
		fmt.Print("auth\n")
	}
}