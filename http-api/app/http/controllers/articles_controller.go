package controllers

import (
	"database/sql"
	"fmt"
	"gorm.io/gorm"
	"html/template"
	articleModel "http-api/app/models/article"
	"http-api/app/requests"
	"http-api/pkg/logger"
	"http-api/pkg/route"
	"http-api/pkg/types"
	"net/http"
	"strconv"
)

type ArticlesController struct {}

func (*ArticlesController) Show(w http.ResponseWriter, r *http.Request)  {
	id := route.GetRouteVariable("id", r)
	article, err := articleModel.Get(id)

	if err != nil {
		if err == gorm.ErrRecordNotFound {
			w.WriteHeader(http.StatusNotFound)
			fmt.Fprintf(w, "404 文章未找到")
		} else {
			logger.LogError(err)
			w.WriteHeader(http.StatusInternalServerError)
			fmt.Fprintf(w, "500 服务器内部错误")
		}
	} else {
		tmpl, err := template.New("show.gohtml").Funcs(template.FuncMap{
			"RouteName2URL": route.Name2URL,
			"Int64ToString": types.Int64ToString,
		}).ParseFiles("resources/views/articles/show.gohtml")
		logger.LogError(err)
		tmpl.Execute(w, article)
	}
}

func (*ArticlesController) Index(w http.ResponseWriter, r *http.Request)  {
	articles, err := articleModel.GetAll()
	if err != nil {
		logger.LogError(err)
		w.WriteHeader(http.StatusInternalServerError)
		fmt.Fprintf(w, "500 服务器内部错误")
	} else {
		tmpl, err := template.ParseFiles("resources/views/articles/index.gohtml")
		logger.LogError(err)
		tmpl.Execute(w, articles)
	}
}

func (* ArticlesController) Create(w http.ResponseWriter, r *http.Request)  {
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
	storeURL := route.Name2URL("articles.store")
	fmt.Fprintf(w, html, storeURL)
}

type ArticlesFormData struct {
	Title, Body string
	URL 	string
	Errors 	map[string][]string
}

func (*ArticlesController)Store(w http.ResponseWriter, r *http.Request) {

	title := r.PostFormValue("title")
	body := r.PostFormValue("body")

	errors := requests.ValidateArticleForm(articleModel.Article{
		Title: title,
		Body: body,
	})

	if len(errors) != 0 {
		storeURL := route.Name2URL("articles.store")
		data := ArticlesFormData{
			Title:  title,
			Body:   body,
			URL:    storeURL,
			Errors: errors,
		}
		tmpl, err := template.ParseFiles("resources/views/articles/create.gohtml")
		if err != nil {
			panic(err)
		}
		tmpl.Execute(w, data)
	} else {
		newArticle := articleModel.Article{
			Title: title,
			Body: body,
		}
		err := newArticle.Create()
		if newArticle.ID > 0 {
			fmt.Fprintf(w, "插入成功， ID为 "+strconv.FormatInt(newArticle.ID, 10))
		} else {
			w.WriteHeader(http.StatusInternalServerError)
			fmt.Fprintf(w, "500 服务器内容错误")
			logger.LogError(err)
		}
	}
}

func (*ArticlesController)Update(w http.ResponseWriter, r *http.Request)  {
	id := route.GetRouteVariable("id", r)
	article, err := articleModel.Get(id)
	if err != nil {
		if err == sql.ErrNoRows {
			w.WriteHeader(http.StatusNotFound)
			fmt.Fprintf(w, "404 没有这个文章")
		} else {
			w.WriteHeader(http.StatusInternalServerError)
			fmt.Fprintf(w, "404 服务器内部错误")
		}
	} else {
		title := r.PostFormValue("title")
		body := r.PostFormValue("body")
		errors := requests.ValidateArticleForm(articleModel.Article{
			Title: title,
			Body: body,
		})
		// 验证标题
		if len(errors) == 0 {
			article.Title = title
			article.Body = body
			rowsAffected, err := article.Update()
			if err != nil {
				logger.LogError(err)
				w.WriteHeader(http.StatusInternalServerError)
				fmt.Fprintf(w, "500 服务器内部错误")
			} else if rowsAffected > 0 {
				showURL := route.Name2URL("articles.show", "id", id)
				http.Redirect(w, r, showURL, http.StatusFound)
			} else {
				fmt.Fprintf(w, "您没有做任何更改!")
			}
		} else {
			updateURL := route.Name2URL("articles.update", "id", id)
			data := ArticlesFormData{
				Title: title,
				Body: body,
				URL: updateURL,
				Errors: errors,
			}
			tmpl, err := template.ParseFiles("resources/views/articles/edit.gohtml")
			logger.LogError(err)
			tmpl.Execute(w, data)
		}
	}
}


//  编辑文章
func (*ArticlesController) Edit(w http.ResponseWriter, r *http.Request) {
	id := route.GetRouteVariable("id", r)
	article, err := articleModel.Get(id)
	if err != nil {
		if err == sql.ErrNoRows {
			w.WriteHeader(http.StatusNotFound)
			fmt.Fprintf(w, "404 文章未找到")
		} else {
			logger.LogError(err)
			w.WriteHeader(http.StatusInternalServerError)
			fmt.Fprintf(w, "500 服务器内部错误")
		}
	} else {
		updateURL := route.Name2URL("articles.update", "id", id)
		data := ArticlesFormData{
			Title: article.Title,
			Body: article.Body,
			URL: updateURL,
			Errors: nil,
		}

		tmpl, err := template.ParseFiles("resources/views/articles/edit.gohtml")
		logger.LogError(err)
		tmpl.Execute(w, data)
	}
}

func (*ArticlesController) Delete(w http.ResponseWriter, r *http.Request) {
	id := route.GetRouteVariable("id", r)
	article, err := articleModel.Get(id)
	if err != nil {
		if err ==  sql.ErrNoRows {
			w.WriteHeader(http.StatusNotFound)
			fmt.Fprintf(w, "文章未找到")
		} else {
			logger.LogError(err)
			w.WriteHeader(http.StatusInternalServerError)
			fmt.Fprintf(w, "500 服务器内部错误")
		}
	} else {
		rowAffected, err := article.Delete()
		if err != nil {
			logger.LogError(err)
			w.WriteHeader(http.StatusInternalServerError)
			fmt.Fprintf(w, "500 服务器内部错误")
		} else {
			if rowAffected > 0 {
				indexURL := route.Name2URL("articles.index")
				http.Redirect(w, r, indexURL, http.StatusFound)
			} else {
				w.WriteHeader(http.StatusNotFound)
				fmt.Fprintf(w, "404 文章未找到")
			}
		}
	}
}

