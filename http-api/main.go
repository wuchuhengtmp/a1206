package main

import (
	"fmt"
	"net/http"
	"strings"
)

func main() {
	http.HandleFunc("/", handlerFunc)
	http.HandleFunc("/about/", handlerAbountFunc)
	http.ListenAndServe(":3000", nil)
}

func handlerFunc(w http.ResponseWriter, r *http.Request)  {
	w.Header().Set("Content-Type", "text/html; charset-8")
	fmt.Fprint(w, "<h1>Hello, </h1>")
}

func handlerAbountFunc(w http.ResponseWriter, r *http.Request)  {
	w.Header().Set("Content-Type", "text/html; charset-8")
	id := strings.SplitN(r.URL.Path, "/", 3)[2]
	fmt.Fprint(w, "id: " + id)
}
