/**
 * The file is part of @wuchuhengtools/request
 *
 * @author wuchuheng<wuchuheng@163.com>
 * @date   2021/4/12
 * @listen MIT
 */
import request from '../utils/request'

/**
 * 获取仪表盘
 * @constructor
 */
export const GetDashboard = () => request.get('/dashboard')
