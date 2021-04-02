/**
 * @Desc    The encrypt is part of http-api
 * @Author  wuchuheng<wuchuheng@163.com>
 * @Blog    https://wuchuheng.com
 * @DATE    2021/4/2
 * @Listen  MIT
 */
package encrypt

import (
	"crypto/sha256"
	"encoding/hex"
	"fmt"
	"http-api/pkg/config"
	"io"
)

// 生成hash
func Hash(str string) string {
	k := config.Env("ENCRYPT_KEY")
	h := sha256.New()
	io.WriteString(h, fmt.Sprintf("%s%s%s", k, str, k))
	shaStr := hex.EncodeToString(h.Sum(nil))
	return shaStr
}

// 验证
func Check(encryptStr string, str string) bool {
	return Hash(str) == encryptStr
}
