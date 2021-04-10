package types

import (
	"fmt"
	"http-api/pkg/logger"
	"strconv"
	"time"
)

func Int64ToString(num int64) string {
	return strconv.FormatInt(num, 10)
}

func StringToInt(str string) int {
	i, err := strconv.Atoi(str)
	if err != nil {
		logger.LogError(err)
	}
	return i
}

/**
 * 格式化时间
 */
type JsonTime time.Time

func (this JsonTime) MarshalJSON() ([]byte, error) {
	var stamp = fmt.Sprintf("\"%s\"", time.Time(this).Format("2006-01-02 15:04:05"))
	return []byte(stamp), nil
}

type UserListPageType []struct {
	ID          int64    `json:"id"`
	Username    string   `json:"username"`
	Avatar      string   `json:"avatar"`
	CreatedAt   JsonTime `json:"createdAt"`
	Nickname    string   `json:"nickname"`
	TotalDevice int64    `json:"totalDevice"`
}
