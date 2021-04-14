/**
 * @Desc    The helper is part of http-api
 * @Author  wuchuheng<wuchuheng@163.com>
 * @Blog    https://wuchuheng.com
 * @DATE    2021/4/14
 * @Listen  MIT
 */
package helper

type SliceType []int64

/**
 * 翻转slice
 */
func (u *SliceType) ReversSlice() SliceType{
	s := *u
	for i, j := 0, len(s)-1; i < j; i, j = i+1, j-1 {
		s[i], s[j] = s[j], s[i]
	}
	return s
}
