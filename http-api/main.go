package main

import (
	"database/sql"
	"fmt"
	"github.com/go-sql-driver/mysql"
	_ "github.com/go-sql-driver/mysql"
	"github.com/gorilla/mux"
	"html/template"
	"log"
	"net/http"
	"net/url"
	"strings"
	"time"
	"unicode/utf8"
)

var router = mux.NewRouter().StrictSlash(true)

var db *sql.DB

func initDB()  {
	 config := mysql.Config{
		User: "wuchuheng_tmp",
		Passwd: "wuchuheng_tmp",
		Addr: "192.168.0.42",
		Net: "tcp",
		DBName: "wuchuheng_tmp",
		AllowNativePasswords: true,
	}
	// 准备数据库连接池
	var err error
	db, err = sql.Open("mysql", config.FormatDSN())
	checkError(err)

	// 设置最大连接数
	db.SetMaxOpenConns(25)
	// 设置最大空闲连接数
	db.SetMaxIdleConns(25)
	// 设置每个链接的过期时间
	db.SetConnMaxLifetime(5 * time.Minute)

	// 尝试连接，失败会报错
	err = db.Ping()
	checkError(err)
}

func checkError(err error) {
	if err != nil {
		log.Fatal(err)
	}
}

func createTables()  {
	createArticlesSQL := `
		CREATE TABLE IF NOT EXISTS articles(
			id bigint(20) PRIMARY KEY AUTO_INCREMENT NOT NULL,
			title varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
			body longtext COLLATE utf8mb4_unicode_ci
		);
	`
	_, err := db.Exec(createArticlesSQL)
	checkError(err)
}

func main() {
	initDB()
	createTables()
	router.HandleFunc("/", homeHandle).Methods("GET").Name("home")
	router.HandleFunc("/about", aboutHandler).Methods("GET").Name("about")
	router.HandleFunc("/articles/{id:\\d+}", articlesShowHandler).Methods("GET").Name("article.show")
	router.HandleFunc("/articles", articlesIndexHandler).Methods("GET").Name("articles.index")
	router.HandleFunc("/articles/create", articlesCreateHandler).Methods("GET").Name("articles.create")
	router.HandleFunc("/articles/store", articleStoreHandler).Methods("POST").Name("articles.store")
	router.NotFoundHandler = http.HandlerFunc(notFoundHandler)
	router.Use(forceHTMLMiddleware)
	http.ListenAndServe(":3000", removeTrailingSlash(router))
}

func removeTrailingSlash(next http.Handler) http.Handler  {
	return http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {
		if r.URL.Path != "/" {
			r.URL.Path = strings.TrimSuffix(r.URL.Path, "/")
		}
		next.ServeHTTP(w, r)
	})
}

// 首页
func homeHandle(w http.ResponseWriter, r *http.Request)  {
	fmt.Fprint(w, "文章首页")
}

// 关于
func aboutHandler(w http.ResponseWriter, r *http.Request)  {
	fmt.Fprintf(w, "about")
}

// 文章详情
func articlesShowHandler(w http.ResponseWriter, r *http.Request)  {
	fmt.Fprintf(w, "article detail" + r.RequestURI)
}

// 文章列表
func articlesIndexHandler(w http.ResponseWriter, r *http.Request)  {
	fmt.Fprintf(w, "articles")
}

type ArticlesFormData struct {
	Title, Body string
	URL 	*url.URL
	Errors 	map[string]string
}

// 添加文章
func articleStoreHandler(w http.ResponseWriter, r *http.Request)  {
	title := r.PostFormValue("title")
	body := r.PostFormValue("body")
	errors := make(map[string]string)

	// 验证标题
	if title == "" {
		errors["title"] = "标题不能为空"
	} else if len(title) < 3 || len(title) > 40 {
		errors["title"] = "标题需介于3-40"
	}
	// 验证内容
	if body == "" {
		errors["body"] = "内容不能为空"
	} else if utf8.RuneCountInString(body) < 10 {
		errors["body"] = "内容长度需大于或等于10个字节"
	}

	if len(errors) != 0 {
		storeURL, _ := router.Get("articles.store").URL()
		data := ArticlesFormData{
			Title: title,
			Body: body,
			URL: storeURL,
			Errors: errors,
		}
		tmpl, err := template.ParseFiles("resources/views/articles/create.gohtml")
		if err != nil {
			panic(err)
		}
		tmpl.Execute(w, data)
	} else {
		fmt.Fprintf(w, "title: %v<br />", title)
		fmt.Fprintf(w, "body: %v <br />", body)
	}
}

// 404 错误
func notFoundHandler(w http.ResponseWriter, r *http.Request)  {
	fmt.Fprintf(w, "404")
}

// 中间件
func forceHTMLMiddleware(h http.Handler) http.Handler {
	return http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {
		// 1. 设置标头
		w.Header().Set("Content-Type", "text/html; charset=utf-8")
		// 2. 继续处理请求
		h.ServeHTTP(w, r)
	})
}

// 添加博文
func articlesCreateHandler(w http.ResponseWriter, r *http.Request)  {
	html := `
		<!DOCTYPE html>
		<html lang="en">
		<head>
			<title>创建文章 —— 我的技术博客</title>
		</head>
		<body>
			<form action="%s" method="post">
				<p><input type="text" name="title"></p>
				<p><textarea name="body" cols="30" rows="10"></textarea></p>
				<p><button type="submit">提交</button></p>
			</form>
		</body>
		</html>
			`
	storeURL, _ := router.Get("articles.store").URL()
	fmt.Fprintf(w, html, storeURL)
}
