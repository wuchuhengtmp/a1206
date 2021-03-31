package model

import (
	"gorm.io/driver/mysql"
	"gorm.io/gorm"
	gormlogger "gorm.io/gorm/logger"
	"http-api/pkg/logger"
)

var DB *gorm.DB

func ConnectDB() *gorm.DB {
	var err error
	config := mysql.New(
		mysql.Config{
			DSN: "wuchuheng_tmp:wuchuheng_tmp@tcp(192.168.0.42:3306)/wuchuheng_tmp?charset=utf8&parseTime=True&loc=Local",
		})
	DB, err = gorm.Open(config, &gorm.Config{
		Logger: gormlogger.Default.LogMode(gormlogger.Warn),
	})
	logger.LogError(err)
	return DB
}
