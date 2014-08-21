<b>全投稿数：<span style="color:#FF8C00">{val all_comment_count}件</span> 平均投稿数：<span style="color:#FF8C00">{val average_comment_count}件/時間</span></b> ({val register_datetime}時点)
<div class="top_comment">
{val top_comment}
</div>
<div style="text-align: right">{val top_no} ：<b class="top_hn">{val top_name}</b></span>&nbsp;<span style="color:#a9a9a9">{val top_time} ID:{val top_id}</span></div>

%%%advertisement%%%

<!--{each comment_list}-->
{val comment_list/no} ：<span style="color:#008000">{val comment_list/name}</span>&nbsp;<span style="color:#a9a9a9">{val comment_list/time} ID:</span><!--{def comment_list/overlap_flg}--><span style="color:#FF8C00"><!--{/def}--><!--{ndef comment_list/overlap_flg}--><span style="color:#a9a9a9"><!--{/def}-->{val comment_list/id}</span>
</span>
<!--{def comment_list/reply_parents}-->
<span style="color:#0000ff"><b>{val comment_list/comment}</b></span>
<!--{/def}-->
<!--{def comment_list/reply_child}-->
<b class="reply_parents">&gt;&gt;{val comment_list/reply_no}</b>
<span style="color:#8b0000"><b>{val comment_list/comment}</b></span>
<!--{/def}-->
<!--{def comment_list/no_reply}-->
{val comment_list/comment}
<!--{/def}-->
<p><br></p>
<!--{/each}-->

<div style="text-align: right">引用元：{val thread_url}</div>
