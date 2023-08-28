const Home = () => import(/* webpackChunkName: 'js/chunks/Home' */'./view/Home.vue') ;
const Register = () => import(/* webpackChunkName: 'js/chunks/Register'*/'./view/Auth/Register.vue');
const Login = () => import(/* webpackChunkName: 'js/chunks/Login'*/'./view/Auth/Login.vue');
const Verify = () => import(/* webpackChunkName: 'js/chunks/Verify'*/'./view/Auth/Verify.vue');
const ResetPassword = () => import(/* webpackChunkName: 'js/chunks/ResetPassword'*/'./view/Auth/ResetPassword.vue');
const ResetEmailPassword = () => import(/* webpackChunkName: 'js/chunks/ResetEmailPassword'*/'./view/Auth/ResetEmailPassword.vue');
const Header = () => import(/* webpackChunkName: 'js/chunks/Header'*/'./view/layout/Header.vue');
const Profile = () => import(/* webpackChunkName: 'js/chunks/Profile'*/'./view/User/Profile.vue');
const UserPosts = () => import(/* webpackChunkName: 'js/chunks/UserPosts'*/ './view/User/UserPosts.vue');
const UserLikedPosts = () => import(/* webpackChunkName: 'js/chunks/UserLikedPosts'*/'./view/User/UserLikedPosts.vue');
const UserBookmarkedPosts = () => import(/* webpackChunkName: 'js/chunks/UserBookmarkedPosts'*/'./view/User/UserBookmarkedPosts.vue');
const MyPosts = () => import(/* webpackChunkName: 'js/chunks/MyPosts'*/ './view/post/MyPosts.vue');
const SavePost = () => import(/* webpackChunkName: 'js/chunks/SavePost'*/'./view/post/SavePost.vue') ;
const UpdatePost = () => import(/* webpackChunkName: 'js/chunks/UpdatePost'*/'./view/post/updatePost.vue');
const PostShow = () => import(/* webpackChunkName: 'js/chunks/PostShow'*/'./view/post/PostShow.vue');
const PostCreate = () => import(/* webpackChunkName: 'js/chunks/PostCreate'*/'./view/post/PostCreate.vue');
const PostEdit = () => import(/* webpackChunkName: 'js/chunks/PostEdit'*/'./view/post/PostEdit.vue') ;
const PostCategory = () => import(/* webpackChunkName: 'js/chunks/PostCategory'*/'./view/post/PostCategory.vue');
const Index = () => import(/* webpackChunkName: 'js/chunks/Index'*/'./view/Search/Index.vue');
const Dashboard = () => import(/* webpackChunkName: 'js/chunks/Dashboard'*/'./view/Admin/Dashboard.vue');
const UserIndex = () => import(/* webpackChunkName: 'js/chunks/UserIndex'*/'./view/Admin/User/UserIndex.vue');
const UserCreate = () => import(/* webpackChunkName: 'js/chunks/UserCreate'*/'./view/Admin/User/UserCreate.vue');
const UserEdit = () => import(/* webpackChunkName: 'js/chunks/UserEdit'*/'./view/Admin/User/UserEdit.vue');
const PostIndex = () => import(/* webpackChunkName: 'js/chunks/PostIndex'*/'./view/Admin/Post/PostIndex.vue');
const AdminPostEdit = () => import(/* webpackChunkName: 'js/chunks/AdminPostEdit'*/'./view/Admin/Post/PostEdit.vue');
const AdminDraftIndex = () => import(/* webpackChunkName: 'js/chunks/AdminDraftIndex'*/'./view/Admin/Draft/DraftIndex.vue');
const AdminCategoryIndex = () => import(/* webpackChunkName: 'js/chunks/AdminCategoryIndex'*/'./view/Admin/Category/CategoryIndex.vue');
const AdminCategoryCreate = () => import(/* webpackChunkName: 'js/chunks/AdminCategoryCreate'*/'./view/Admin/Category/CategoryCreate.vue');
const AdminCategoryEdit = () => import(/* webpackChunkName: 'js/chunks/AdminCategoryEdit'*/'./view/Admin/Category/CategoryEdit.vue');
const AdminCommentIndex = () => import(/* webpackChunkName: 'js/chunks/AdminCommentIndex'*/'./view/Admin/Comment/CommentIndex.vue');
const AdminRoleIndex = () => import(/* webpackChunkName: 'js/chunks/AdminRoleIndex'*/'./view/Admin/Roles/RoleIndex.vue');
const AdminRoleCreate = () => import(/* webpackChunkName: 'js/chunks/AdminRoleCreate'*/'./view/Admin/Roles/RoleCreate.vue');
const AdminRoleEdit = () => import(/* webpackChunkName: 'js/chunks/AdminRoleEdit'*/'./view/Admin/Roles/RoleEdit.vue');
const AdminLayout = () => import(/* webpackChunkName: 'js/chunks/AdminLayout'*/'./view/layout/AdminLayout.vue');
const NotFound = () => import(/* webpackChunkName: 'js/chunks/NotFound'*/'./view/Error/NotFound.vue');
const AccessDenied = () => import(/* webpackChunkName: 'js/chunks/AccessDenied'*/'./view/Error/AccessDenied.vue');


const routes = [
    {
        path: '/',
        component: Header,
        children: [
            {
                path: "",
                component: Home,
                name: 'home',
                meta: {
                    title: " صفحه اصلی - وبلاگ شخصی"
                }
            },
            {
                path: "posts/draft",
                component: MyPosts,
                name: 'my-posts',
                meta: {
                    auth: true,
                    title: "  پست های من - وبلاگ شخصی"
                }
            },
            {
                path: "post/:slug",
                component: PostShow,
                name: 'post-show'
            },
            {
                path: "posts/create",
                component: PostCreate,
                name: 'create-post',
                meta: {
                    auth: true,
                    verified: true,
                    title: "    ایجاد پست - وبلاگ شخصی"
                }
            },
            {
                path: "posts/:slug/edit",
                component: PostEdit,
                name: 'edit-post',
                meta: {
                    auth: true,
                    verified: true,
                    title: "    ویرایش پست - وبلاگ شخصی"
                }
            },
            {
                path: 'posts/category/:slug',
                component: PostCategory,
                name: 'post-category'
            },
            {
                path: "posts/:slug/save",
                component: UpdatePost,
                name: 'update-post',
                meta: {
                    auth: true,
                    verified: true,
                    title: "     ثبت ویرایش پست - وبلاگ شخصی"
                }
            },
            {
                path: "drafts/:link",
                component: PostCreate,
                name: 'update-draft',
                meta: {
                    auth: true,
                    verified: true,
                    title: "    ویرایش پست - وبلاگ شخصی"
                }
            },
            {
                path: "drafts/:link/save",
                component: SavePost,
                name: 'save-post',
                meta: {
                    auth: true,
                    verified: true,
                    title: "    ذخیره پست - وبلاگ شخصی"
                }
            },
            {
                path: "profile",
                component: Profile,
                name: 'profile',
                meta: {
                    auth: true,
                    verified: true,
                    title: "پروفایل من - وبلاگ شخصی"
                }
            },
            {
                path: "liked-posts",
                component: UserLikedPosts,
                name: 'liked-posts',
                meta: {
                    auth: true,
                    verified: true
                }
            },
            {
                path: "bookmarked-posts",
                component: UserBookmarkedPosts,
                name: 'bookmarked-posts',
                meta: {
                    auth: true,
                    verified: true
                }
            },
            {
                path: "search/:type",
                component: Index,
                name: 'search'
            },
            {
                path: "@:username",
                component: UserPosts,
                name: 'user-posts',
            }
        ]
    },
    {
        path: '/login',
        component: Login,
        name: 'login',
        meta: {
            guest: true,
            title: " صفحه ورود - وبلاگ شخصی"
        }
    },
    {
        path: '/register',
        component: Register,
        name: 'register',
        meta: {
            guest: true,
            title: " صفحه ثبت نام - وبلاگ شخصی"
        }
    },
    {
        path: '/email/verify',
        component: Verify,
        name: 'email-verify',
        meta: {
            auth: true,
            title: " تایید ایمیل - وبلاگ شخصی"
        }
    },
    {
        path: '/reset/password',
        component: ResetEmailPassword,
        name: 'reset-email-password',
        meta: {
            guest: true,
            title: " ایمیل بازیابی رمز عبور - وبلاگ شخصی"
        }
    },
    {
        path: '/reset/password/:token',
        component: ResetPassword,
        name: 'reset-password',
        meta: {
            guest: true,
            title: " بازیابی رمز عبور - وبلاگ شخصی"
        }
    },
    {
        path: '/admin',
        component: AdminLayout,
        meta: {
            auth: true,
            verified: true
        },
        children: [
            {
                path: 'dashboard',
                component: Dashboard,
                name: 'admin-dashboard',
                meta: {
                    title: " داشبورد - وبلاگ شخصی"
                }
            },
            {
                path: 'users',
                component: UserIndex,
                name: 'admin-user-index',
                meta: {
                    title: " مدیریت کاربران "
                }
            },
            {
                path: 'users/create',
                component: UserCreate,
                name: 'admin-user-create',
                meta: {
                    title: " ساخت کاربر"
                }
            },
            {
                path: 'users/:id',
                component: UserEdit,
                name: 'admin-user-edit',
                meta: {
                    title: " ویرایش کاربر"
                }
            },
            {
                path: 'posts',
                component: PostIndex,
                name: 'admin-post-index',
                meta: {
                    title: " مدیریت پست ها "
                }
            },
            {
                path: 'posts/:id',
                component: AdminPostEdit,
                name: 'admin-post-edit',
                meta: {
                    title: " ویرایش پست ها "
                }
            },
            {
                path: 'drafts',
                component: AdminDraftIndex,
                name: 'admin-draft-index',
                meta: {
                    title: " مدیریت  پیش نویس ها "
                }
            },
            {
                path: 'categories',
                component: AdminCategoryIndex,
                name: 'admin-category-index',
                meta: {
                    title: " مدیریت دسته بندی ها "
                }
            },
            {
                path: 'categories/create',
                component: AdminCategoryCreate,
                name: 'admin-category-create',
                meta: {
                    title: " ساخت دسته بندی"
                }
            },
            {
                path: 'categories/:id',
                component: AdminCategoryEdit,
                name: 'admin-category-edit',
                meta: {
                    title: " ویرایش دسته بندی"
                }
            },
            {
                path: 'comments',
                component: AdminCommentIndex,
                name: 'admin-comment-index',
                meta: {
                    title: " مدیریت کامنت ها "
                }
            },
            {
                path: 'roles',
                component: AdminRoleIndex,
                name: 'admin-role-index',
                meta: {
                    title: " مدیریت نقش ها "
                }
            },
            {
                path: 'roles',
                component: AdminRoleCreate,
                name: 'admin-role-create',
                meta: {
                    title: " ساخت نقش کاربر"
                }
            },
            {
                path: 'roles/:id',
                component: AdminRoleEdit,
                name: 'admin-role-edit',
                meta: {
                    title: " ویرایش نقش کاربر"
                }
            },
        ]
    },
    {
        path: '/404',
        component: NotFound,
        name: 'not-found'
    },
    {
        path: '/403',
        component: AccessDenied,
        name: 'access-denied'
    },
    {
        path: '/:match(.*)',
        component: NotFound,
        redirect: '/404'
    }
]

export default routes;