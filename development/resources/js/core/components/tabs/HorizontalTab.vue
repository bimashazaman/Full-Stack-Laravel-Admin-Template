<template>
    <div class="horizontal-tab">
        <div class="card border-0" :class="{ 'card-with-shadow' : elevated }">
            <nav class="d-flex">
                <div class="nav" v-if="isScrollAble">
                    <button :class="`${tabClass} btn btn-link btn_cus`" @mousedown="scrollLeft" @mouseup="scrollLeft"><app-icon name="chevron-left"/></button>
                </div>
                <div id="tabs_wrapper" class="nav-tabs hide-scrollbar w-100" ref="tabs_wrapper">
                    <div draggable="true" @mousedown="drag" @mouseup="drag" @mouseleave="drag" class="nav flex-nowrap dragable" style="white-space: nowrap;">
                        <a v-for="(tab, index) in filteredTab" :key="index"
                           class="nav-item text-capitalize"
                           :class="`${tabClass} ${currentIndex == index ? 'active': ''}`"
                           data-toggle="tab" :href="'#'+tab.name+'-'+index"
                           @click.prevent.exact="loadComponent(tab,index)">
                            <span v-if="tab.icon" class="mr-2"><app-icon :name="tab.icon"/></span>{{tab.name}}
                        </a>
                    </div>
                </div>
                <div class="nav" v-if="isScrollAble">
                    <button :class="`${tabClass} btn btn-link btn_cus`" @mousedown="scrollRight" @mouseup="scrollRight"><app-icon name="chevron-right"/></button>
                </div>
            </nav>
            <div class="tab-content" :class="componentClass || bodyClass">
                <div class="tab-pane fade show active" :id="componentId">
                    <component :is="componentName" :props="componentProps" :key="`dynamic-component-${componentProps? componentProps.id : ''}`"></component>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {TabMixin} from './mixins/TabMixin';

export default {
    name: "HorizontalTab",
    mixins: [TabMixin],
    data() {
        return {
            isScrollAble: false,
            isHold: false,
            isDragable: false,
        }
    },
    methods: {
        drag(event){
            let tabs_wrapper = this.$refs.tabs_wrapper;
            this.isDragable = event.type === 'mousedown';

            let pos = {
                left: tabs_wrapper.scrollLeft,
                top: tabs_wrapper.scrollTop,
                x: event.clientX,
                y: event.clientY,
            };
            let mouseMoveHandler = (event) => {
                event.preventDefault();
                if(!this.isDragable) return;
                const dx = event.clientX - pos.x;
                const dy = event.clientY - pos.y;
                tabs_wrapper.scrollTop = pos.top - dy;
                tabs_wrapper.scrollLeft = pos.left - dx;
            }
            if(this.isDragable) {
                tabs_wrapper.addEventListener('mousemove', mouseMoveHandler);
            }
        },
        move(e){
            console.log(e)
        },
        release(e){
            console.log(e)
        },
        scrollRight(event){
            let tabs_wrapper = this.$refs.tabs_wrapper;
            this.isHold = event.type === 'mousedown';
            let start = () => {
                if(this.isHold) {
                    tabs_wrapper.scrollLeft += 20;
                    requestAnimationFrame(start)
                }
            }
            start();
        },
        scrollLeft(event){
            let tabs_wrapper = this.$refs.tabs_wrapper;
            this.isHold = event.type === 'mousedown';
            let start = () => {
                if(this.isHold) {
                    tabs_wrapper.scrollLeft -= 20;
                    requestAnimationFrame(start)
                }
            }
            start();
        },
        setScrollable() {
            let tabs_wrapper = this.$refs.tabs_wrapper;
            this.isScrollAble = tabs_wrapper.scrollWidth > tabs_wrapper.offsetWidth;
        },
    },
    mounted(){
        setTimeout(() => {
            this.setScrollable();
        });
    },
    created(){
        window.addEventListener('resize', ()=> {
            this.setScrollable();
        })
    }
}
</script>

<style scoped>
.btn_cus {
    padding-left: 1px !important;
    padding-right: 1px !important;
    border-radius: 0 !important;
}
.dragable {
    cursor: pointer;
}
.dragable:focus {
    cursor: grabbing;
    cursor: -webkit-grabbing;
}
</style>
