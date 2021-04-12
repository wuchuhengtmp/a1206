/**
 * The file is part of @wuchuhengtools/request
 *
 * @author wuchuheng<wuchuheng@163.com>
 * @date   2021/4/12
 * @listen MIT
 */
import request from '../utils/request'

/**
 * 更新关于我们
 * @param content
 * @returns {Promise<AxiosResponse<any>>}
 * @constructor
 */
export const UpdateContent = (content: string) => request.post('/about', { content })

/**
 * 获取关于我们
 * @constructor
 */
export const GetContent = () => request.get('/about')
