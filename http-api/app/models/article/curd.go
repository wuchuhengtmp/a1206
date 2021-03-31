package article

import (
	"http-api/pkg/model"
	"http-api/pkg/types"
)

func Get(idstr string) (Article, error)  {
	var article Article
	id := types.StringToInt(idstr)
	if err := model.DB.First(&article, id).Error; err != nil {
		return article, err
	}
	return article, nil
}
