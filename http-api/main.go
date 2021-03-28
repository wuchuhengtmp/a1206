package main

import (
	"fmt"
	"github.com/gorilla/mux"
	"net/http"
)

func main() {
	router := mux.NewRouter()
	router.HandleFunc("/", homeHandle).Methods("GET").Name("home")
	router.HandleFunc("/about", aboutHandler).Methods("GET").Name("about")
	router.HandleFunc("/articles/{id:\\d+}", articlesShowHandler).Methods("GET").Name("article.show")
	router.HandleFunc("/articles", articlesIndexHandler).Methods("GET").Name("articles.index")
	router.HandleFunc("/articles", articleStoreHandler).Methods("POST").Name("articles.index")
	router.NotFoundHandler = http.HandlerFunc(notFoundHandler)

	http.ListenAndServe(":3000", router)
}

// 首页
func homeHandle(w http.ResponseWriter, r *http.Request)  {
	w.Header().Set("Content-Type", "text/html; charset=utf-8")
	fmt.Fprint(w, "文章首页")
}

// 关于
func aboutHandler(w http.ResponseWriter, r *http.Request)  {
	w.Header().Set("Content-Type", "text/html; charset=utf-8")
	fmt.Fprintf(w, "about")
}

// 文章详情
func articlesShowHandler(w http.ResponseWriter, r *http.Request)  {
	w.Header().Set("Content-Type", "text/html; charset=utf-8")
	fmt.Fprintf(w, "article detail" + r.RequestURI)
}

// 文章列表
func articlesIndexHandler(w http.ResponseWriter, r *http.Request)  {
	w.Header().Set("Content-Type", "text/html; charset=utf-8")
	fmt.Fprintf(w, "articles")
}

// 添加文章
func articleStoreHandler(w http.ResponseWriter, r *http.Request)  {
	w.Header().Set("Content-Type", "text/html; charset=utf-8")
	fmt.Fprintf(w, "stored the article" + r.RequestURI)
}

// 404 错误
func notFoundHandler(w http.ResponseWriter, r *http.Request)  {
	fmt.Fprintf(w, "404")
}

