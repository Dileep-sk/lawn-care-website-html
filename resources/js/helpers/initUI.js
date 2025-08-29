import { initSidebarToggle } from './sidebar'
import { hideLoader, fadeInBody } from './loader'
import { initBarChart, initPieChart } from './chart'

export function initUI() {
    initSidebarToggle()
    hideLoader()
    // fadeInBody()
    // initBarChart()
    initPieChart()
}
