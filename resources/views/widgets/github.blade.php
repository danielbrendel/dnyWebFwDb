<div class="github">
    <div class="github-title">
        <a href="{{ $github->html_url }}" class="has-text-weight-bold">{{ $github->full_name }}</a>&nbsp;&nbsp;<i class="fas fa-star"></i>&nbsp;{{ $github->stargazers_count }}
    </div>

    <div class="github-infos">
        <div class="github-info-last-commit">
            <i class="fas fa-arrow-circle-up"></i>&nbsp;{{ __('app.last_commit', ['diff' => $github->last_commit_diff]) }}
        </div>

        <div class="github-info-issues">
            <i class="far fa-dot-circle"></i>&nbsp;{{ __('app.open_issues', ['count' => $github->open_issues]) }}
        </div>

        <div class="github-info-forks">
            <i class="fas fa-code-branch"></i>&nbsp;{{ __('app.forks', ['count' => $github->forks_count]) }}
        </div>
    </div>

    @if ($github->commit_day_count <= 30)
        <div class="github-active github-repo-active"><i class="fas fa-check-square"></i>&nbsp;{{ __('app.repo_seems_active') }}</div>
    @else
        <div class="github-active github-repo-inactive">{{ __('app.repo_seems_inactive') }}</div>
    @endif
</div>