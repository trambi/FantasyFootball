# TournamentCoreBundle API

## Index and Edition routes

* _index_: Return all availables route ;
* _Editions_: Return all editions of the tournament ;
* _EditionList_: Alias of _Editions_ ;
* _Edition/current_: Return the current - last - edition.

## Coach routes

* _CoachList/{edition}_: Return the coach list of edition {edition} ;
* _Coachs/{edition}_: Alias of _CoachList/{edition}_ ;
* _Coach/{coachId}_: Return the coach with id {coachId}.

## Match routes

* _MatchList/{edition}/{round}_: Return match list of the round {round} of edition {edition} ;
* _Matchs/{edition}/{round}_: Alias _MatchList/{edition}/{round}_ ;
* _PlayedMatchs/{edition}/{round}_: Return played match list of the round {round} of edition {edition} ;
* _PlayedMatchList/{edition}/{round}_: Alias _PlayedMatchs/{edition}/{round}_ ;
* _ToPlayMatchList/{edition}/{round}_: Return match to play list of the round {round} of edition {edition} ;
* _ToPlayMatchs/{edition}/{round}_: Alias _ToPlayMatchList/{edition}/{round}_ ;
* _MatchListByCoach/{coachId}_: Return match list of coach with id {coachId} ;
* _/MatchsByCoach/{coachId}_: Alias _MatchListByCoach/{coachId}_ ;
* _MatchsByCoachTeam/{coachTeamId}_ :Returm match list of coachTeam with id {coachTeamId} ;
* _MatchListByCoachTeam/{coachTeamId}_: Alias _MatchsByCoachTeam/{coachTeamId}_

## Coach team routes

* _CoachTeams/{edition}_: Return the list of coach Teams of edition {edition} ;
* _CoachTeamList/{edition}_: Alias _CoachTeams/{edition}_ ;
* _CoachTeam/{coachTeamId}_: Return coach Team with id {coachTeamId}.

## Ranking routes

* _ranking/coach/main/{edition}_: Return main ranking for coach for edition {edition} ;
* _ranking/coach/td/{edition}_: Return scored touchdown ranking for coach for edition {edition} ;
* _ranking/coach/casualties/{edition}_: Return casualties ranking for coach for edition {edition} ;
* _ranking/coach/completions/{edition}_: Return completions ranking for coach for edition {edition} ;
* _ranking/coach/fouls/{edition}_: Return fouls ranking for coach for edition {edition} ;
* _ranking/coach/comeback/{edition}_: Return best comeback ranking for coach for edition {edition} ;
* _ranking/coach/defense/{edition}_: Return inflicted touchdown ranking for coach for edition {edition} ;
* _ranking/coachTeam/main/{edition}_: Return main ranking for coach team for edition {edition} ;
* _ranking/coachTeam/td/{edition}_: Return scored touchdown ranking for coach team for edition {edition} ;
* _ranking/coachTeam/casualties/{edition}_: Return casualties ranking for coach team for edition {edition} ;
* _ranking/coachTeam/completions/{edition}_: Return completions ranking for coach team for edition {edition} ;
* _ranking/coachTeam/fouls/{edition}_: Return fouls ranking for coach team for edition {edition} ;
* _ranking/coachTeam/comeback/{edition}_: Return best comeback ranking for coach team for edition {edition} ;
* _ranking/coachTeam/defense/{edition}_: Return inflicted touchdown ranking for coach team for edition {edition} ;
