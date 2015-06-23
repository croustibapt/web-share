<!-- Title -->
<div id="div-share-details-comments-header" class="container" style="padding-top: 50px; margin-top: -50px;">
    <h3 ng-if="(comments.length == 0)">Aucun commentaire</h3>
    <h3 ng-if="(comments.length == 1)">1 commentaire</h3>
    <h3 ng-if="(comments.length > 1)">{{ comments.length }} commentaires</h3>
</div>

<!-- Comments -->
<div id="div-share-details-comments" class="container">
    <div class="row">
        <div class="col-md-12">
            <div id="div-share-details-comments-list" ng-if="(comments.length > 0)">
                <!-- All comments -->
                <div ng-repeat="comment in comments">

                    <!-- Creator comment -->
                    <div ng-if="(shareUserExternalId === comment.user.external_id)" class="media">
                        <div class="media-body">
                            <blockquote class="blockquote-reverse">

                                <h4 class="media-heading">{{ comment.user.username }}</h4>

                                <p class="lead">{{ comment.message }}</p>

                                <footer>
                                    <span>{{ comment.moment_created_time_ago }}</span>
                                </footer>

                            </blockquote>
                        </div>
                        <div class="media-right">
                            <img class="comment-user-img img-circle img-thumbnail" ng-src="https://graph.facebook.com/v2.3/{{ comment.user.external_id }}/picture" />
                        </div>
                    </div>

                    <!-- Other user comment -->
                    <div ng-if="(shareUserExternalId !== comment.user.external_id)" class="media">
                        <div class="media-left">
                            <img class="comment-user-img img-circle img-thumbnail" ng-src="https://graph.facebook.com/v2.3/{{ comment.user.external_id }}/picture" />
                        </div>
                        <div class="media-body">
                            <blockquote class="blockquote-normal">

                                <h4 class="media-heading">{{ comment.user.username }}</h4>

                                <p class="lead">{{ comment.message }}</p>

                                <footer>
                                    <span>{{ comment.moment_created_time_ago }}</span>
                                </footer>

                            </blockquote>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <?php echo $this->element('pagination'); ?>
            </div>

            <!-- No comments -->
            <!--<div ng-if="(comments.length == 0)">
                <p class="lead text-center">
                    Aucun commentaire
                </p>
            </div>-->
        </div>

        <?php if ($this->LocalUser->isAuthenticated($this)) : ?>

            <div class="col-md-10 div-share-details-comments-editor">
                <textarea id="textarea-comment-add" class="form-control" rows="3" ng-model="message"></textarea>
            </div>
            <div class="col-md-2 div-share-details-comments-editor">
                <div style="padding-right: 15px;">
                    <button id="btn-comment-add" type="submit" class="btn btn-primary" data-loading-text="Sending..." ng-click="onSendButtonClicked($event);">
                        Envoyer
                    </button>
                </div>
            </div>

        <?php else : ?>

            <div class="col-md-12 div-share-details-comments-editor">
                <div class="alert alert-info" role="alert">
                    <strong>Information :</strong> Vous devez être authentifié pour commenter.
                </div>
            </div>

        <?php endif; ?>
    </div>
</div>

<?php if ($this->LocalUser->isAuthenticated($this)) : ?>

<script>
    //On ready
    $(document).ready(function() {
        //Create editor
        new nicEditor({
            buttonList : ['bold','italic','underline', 'link', 'unlink']
        })
        .panelInstance('textarea-comment-add');

        //Initial empty content
        var editor = nicEditors.findEditor('textarea-comment-add');
        editor.setContent('');
    });
</script>

<?php endif; ?>