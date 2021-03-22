#!/usr/bin/env sh

read -p "前端开发模式输入:front-end 后端开发模式输入:back-end 产品开发模式输入:product . 不输入则默认上次选择  " REPLY

if [[ "$REPLY" == "front-end" ]]; then
  # 前端开发
  cat docker-compose.yml.front-end.yml > docker-compose.yml && cat .env.front-end > .env && docker-compose up
  elif [[ "$REPLY" == "back-end" ]]; then
  cat docker-compose.yml.back-end.yml > docker-compose.yml && cat .env.back-end > .env && docker-compose up
  #后端开发
fi