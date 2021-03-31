package tests

import (
	"github.com/stretchr/testify/assert"
	"net/http"
	"testing"
)

func TestHomePage(t *testing.T)  {
	baseURL := "http://localhost:3000"

	var (
		resp *http.Response
		err error
		)
	resp, err = http.Get(baseURL + "/")
	assert.NoError(t, err, "An error occurred, the err was empty")
	assert.Equal(t, 200, resp.StatusCode, "The status code must be 200")
}
