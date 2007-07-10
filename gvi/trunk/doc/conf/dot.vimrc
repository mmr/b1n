"
" Marcio Ribeiro <mmr@b1n.org>
"

"--Files
set wildmode=longest,list
set path=.,~/.vim,$VIMRUNTIME/syntax/
set nobackup
set nowritebackup
set autowriteall
set viminfo='10,\"30,:20,%,n~/.viminfo
au BufReadPost * if line("'\"")|execute("normal `\"")|endif

"-- Colors
  "-- Syntax File
if &t_Co > 2 && has("syntax")
  syntax on
endif
  "-- My Colors
hi Comment  ctermfg=Red
hi Search   ctermbg=Green ctermfg=Black 
  "-- GUI
if has("gui_running")
  colorscheme desert
endif

"--Abreviations
cab W  w
cab Wq wq
cab wQ wq
cab WQ wq
cab Q  q

"--Buffers
nmap <F4> :ls<CR>:e #
nmap <F5> :sil bp<C-M>
nmap <F6> :sil bn<C-M>
nmap <F7> :sil bd<C-M>

"--Windows
nmap <C-j> <C-w>j
nmap <C-k> <C-w>k
nmap <C-h> <C-w>h
nmap <C-l> <C-w>l
"nmap <S-e> :sil resize +15<CR>
"nmap <S-b> :sil resize -15<CR>
"nmap <C-e> :sil vertical resize +15<CR>
"nmap <C-b> :sil vertical resize -15<CR>
 
"--Search
set incsearch
set ignorecase
set smartcase
set magic
map <F2> :sil noh<C-M>
set hlsearch

"--Programming
map <F3> :make&&!%:p:r<C-M>
set autoindent
set showmatch
set expandtab
set shiftwidth=2
set tabstop=2
"set softtabstop=2
set backspace=0
filetype on

"--Misc
set pastetoggle=<f11>
set dictionary=/usr/share/dict/words
set icon    
set esckeys
set nocompatible
set lazyredraw
set visualbell
set ruler
set report=0
set shortmess=filmnrwxt
set showcmd
set laststatus=2
"set textwidth=70
"set wrap
"map <C-a> 1GvG$
