package controllers

import (
	"fmt"
	"gorm.io/gorm"
	"html/template"
	articleModel "http-api/app/models/article"
	"http-api/pkg/logger"
	"http-api/pkg/route"
	"http-api/pkg/types"
	"net/http"
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
