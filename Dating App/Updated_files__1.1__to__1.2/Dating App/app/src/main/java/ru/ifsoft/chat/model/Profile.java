package ru.ifsoft.chat.model;

import android.app.Application;
import android.os.Parcel;
import android.os.Parcelable;
import android.util.Log;

import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

import ru.ifsoft.chat.app.App;
import ru.ifsoft.chat.constants.Constants;
import ru.ifsoft.chat.util.CustomRequest;


public class Profile extends Application implements Constants, Parcelable {

    private long id;

    private int state, sex, year, month, day, verify, itemsCount, likesCount, giftsCount, friendsCount, followingsCount, followersCount, photosCount, allowPhotosComments, allowShowMyBirthday, allowMessages, lastAuthorize;

    private double distance = 0;

    private int relationshipStatus, politicalViews, worldView, personalPriority, importantInOthers, viewsOnSmoking, viewsOnAlcohol, youLooking, youLike;

    private String username, fullname, lowPhotoUrl, bigPhotoUrl, normalPhotoUrl, normalCoverUrl, location, facebookPage, instagramPage, bio, lastAuthorizeDate, lastAuthorizeTimeAgo, createDate;

    private Boolean blocked = false;

    private Boolean inBlackList = false;

    private Boolean follower = false;

    private Boolean friend = false;

    private Boolean follow = false;

    private Boolean online = false;

    private Boolean myLike = false;

    public Profile() {


    }

    public Profile(JSONObject jsonData) {

        try {

            if (!jsonData.getBoolean("error")) {

                this.setRelationshipStatus(jsonData.getInt("iStatus"));
                this.setPoliticalViews(jsonData.getInt("iPoliticalViews"));
                this.setWorldView(jsonData.getInt("iWorldView"));
                this.setPersonalPriority(jsonData.getInt("iPersonalPriority"));
                this.setImportantInOthers(jsonData.getInt("iImportantInOthers"));
                this.setViewsOnSmoking(jsonData.getInt("iSmokingViews"));
                this.setViewsOnAlcohol(jsonData.getInt("iAlcoholViews"));
                this.setYouLooking(jsonData.getInt("iLooking"));
                this.setYouLike(jsonData.getInt("iInterested"));

                this.setId(jsonData.getLong("id"));
                this.setState(jsonData.getInt("state"));
                this.setSex(jsonData.getInt("sex"));
                this.setYear(jsonData.getInt("year"));
                this.setMonth(jsonData.getInt("month"));
                this.setDay(jsonData.getInt("day"));
                this.setUsername(jsonData.getString("username"));
                this.setFullname(jsonData.getString("fullname"));
                this.setLocation(jsonData.getString("location"));
                this.setFacebookPage(jsonData.getString("fb_page"));
                this.setInstagramPage(jsonData.getString("instagram_page"));
                this.setBio(jsonData.getString("status"));
                this.setVerify(jsonData.getInt("verify"));

                this.setLowPhotoUrl(jsonData.getString("lowPhotoUrl"));
                this.setNormalPhotoUrl(jsonData.getString("normalPhotoUrl"));
                this.setBigPhotoUrl(jsonData.getString("bigPhotoUrl"));

                this.setNormalCoverUrl(jsonData.getString("normalCoverUrl"));

                this.setFriendsCount(jsonData.getInt("friendsCount"));
                this.setLikesCount(jsonData.getInt("likesCount"));
                this.setGiftsCount(jsonData.getInt("giftsCount"));
                this.setPhotosCount(jsonData.getInt("photosCount"));

                this.setAllowPhotosComments(jsonData.getInt("allowPhotosComments"));
                this.setAllowMessages(jsonData.getInt("allowMessages"));
                this.setAllowShowMyBirthday(jsonData.getInt("allowShowMyBirthday"));

                this.setInBlackList(jsonData.getBoolean("inBlackList"));
                this.setFollower(jsonData.getBoolean("follower"));
                this.setFriend(jsonData.getBoolean("friend"));
                this.setFollow(jsonData.getBoolean("follow"));
                this.setOnline(jsonData.getBoolean("online"));
                this.setBlocked(jsonData.getBoolean("blocked"));
                this.setMyLike(jsonData.getBoolean("myLike"));

                this.setLastActive(jsonData.getInt("lastAuthorize"));
                this.setLastActiveDate(jsonData.getString("lastAuthorizeDate"));
                this.setLastActiveTimeAgo(jsonData.getString("lastAuthorizeTimeAgo"));

                this.setCreateDate(jsonData.getString("createDate"));

                if (jsonData.has("distance")) {

                    this.setDistance(jsonData.getDouble("distance"));
                }
            }

        } catch (Throwable t) {

            Log.e("Profile", "Could not parse malformed JSON: \"" + jsonData.toString() + "\"");

        } finally {

            Log.d("Profile", jsonData.toString());
        }
    }

    public void setRelationshipStatus(int relationshipStatus) {

        this.relationshipStatus = relationshipStatus;
    }

    public int getRelationshipStatus() {

        return this.relationshipStatus;
    }

    public void setPoliticalViews(int politicalViews) {

        this.politicalViews = politicalViews;
    }

    public int getPoliticalViews() {

        return this.politicalViews;
    }

    public void setWorldView(int worldView) {

        this.worldView = worldView;
    }

    public int getWorldView() {

        return this.worldView;
    }

    public void setPersonalPriority(int personalPriority) {

        this.personalPriority = personalPriority;
    }

    public int getPersonalPriority() {

        return this.personalPriority;
    }

    public void setImportantInOthers(int importantInOthers) {

        this.importantInOthers = importantInOthers;
    }

    public int getImportantInOthers() {

        return this.importantInOthers;
    }

    public void setViewsOnSmoking(int viewsOnSmoking) {

        this.viewsOnSmoking = viewsOnSmoking;
    }

    public int getViewsOnSmoking() {

        return this.viewsOnSmoking;
    }

    public void setViewsOnAlcohol(int viewsOnAlcohol) {

        this.viewsOnAlcohol = viewsOnAlcohol;
    }

    public int getViewsOnAlcohol() {

        return this.viewsOnAlcohol;
    }

    public void setYouLooking(int youLooking) {

        this.youLooking = youLooking;
    }

    public int getYouLooking() {

        return this.youLooking;
    }

    public void setYouLike(int youLike) {

        this.youLike = youLike;
    }

    public int getYouLike() {

        return this.youLike;
    }

    public void setId(long profile_id) {

        this.id = profile_id;
    }

    public long getId() {

        return this.id;
    }

    public void setState(int profileState) {

        this.state = profileState;
    }

    public int getState() {

        return this.state;
    }

    public void setSex(int sex) {

        this.sex = sex;
    }

    public int getSex() {

        return this.sex;
    }

    public void setYear(int year) {

        this.year = year;
    }

    public int getYear() {

        return this.year;
    }

    public void setMonth(int month) {

        this.month = month;
    }

    public int getMonth() {

        return this.month;
    }

    public void setDay(int day) {

        this.day = day;
    }

    public int getDay() {

        return this.day;
    }

    public void setVerify(int profileVerify) {

        this.verify = profileVerify;
    }

    public int getVerify() {

        return this.verify;
    }

    public Boolean isVerify() {

        if (this.verify > 0) {

            return true;
        }

        return false;
    }

    public void setUsername(String profile_username) {

        this.username = profile_username;
    }

    public String getUsername() {

        return this.username;
    }

    public void setFullname(String profile_fullname) {

        this.fullname = profile_fullname;
    }

    public String getFullname() {

        return this.fullname;
    }

    public void setLocation(String location) {

        this.location = location;
    }

    public String getLocation() {

        if (this.location == null) {

            this.location = "";
        }

        return this.location;
    }

    public void setFacebookPage(String facebookPage) {

        this.facebookPage = facebookPage;
    }

    public String getFacebookPage() {

        return this.facebookPage;
    }

    public void setInstagramPage(String instagramPage) {

        this.instagramPage = instagramPage;
    }

    public String getInstagramPage() {

        return this.instagramPage;
    }

    public void setBio(String bio) {

        this.bio = bio;
    }

    public String getBio() {

        return this.bio;
    }

    public void setLowPhotoUrl(String lowPhotoUrl) {

        this.lowPhotoUrl = lowPhotoUrl;
    }

    public String getLowPhotoUrl() {

        return this.lowPhotoUrl;
    }

    public void setBigPhotoUrl(String bigPhotoUrl) {

        this.bigPhotoUrl = bigPhotoUrl;
    }

    public String getBigPhotoUrl() {

        return this.bigPhotoUrl;
    }

    public void setNormalPhotoUrl(String normalPhotoUrl) {

        this.normalPhotoUrl = normalPhotoUrl;
    }

    public String getNormalPhotoUrl() {

        return this.normalPhotoUrl;
    }

    public void setNormalCoverUrl(String normalCoverUrl) {

        this.normalCoverUrl = normalCoverUrl;
    }

    public String getNormalCoverUrl() {

        return this.normalCoverUrl;
    }

    public void setFollowersCount(int followersCount) {

        this.followersCount = followersCount;
    }

    public int getFollowersCount() {

        return this.followersCount;
    }

    public void setFriendsCount(int friendsCount) {

        this.friendsCount = friendsCount;
    }

    public int getFriendsCount() {

        return this.friendsCount;
    }

    public void setItemsCount(int itemsCount) {

        this.itemsCount = itemsCount;
    }

    public int getItemsCount() {

        return this.itemsCount;
    }

    public void setLikesCount(int likesCount) {

        this.likesCount = likesCount;
    }

    public int getLikesCount() {

        return this.likesCount;
    }

    public void setGiftsCount(int giftsCount) {

        this.giftsCount = giftsCount;
    }

    public int getGiftsCount() {

        return this.giftsCount;
    }

    public void setPhotosCount(int photosCount) {

        this.photosCount = photosCount;
    }

    public int getPhotosCount() {

        return this.photosCount;
    }

    public void setFollowingsCount(int followingsCount) {

        this.followingsCount = followingsCount;
    }

    public int getFollowingsCount() {

        return this.followingsCount;
    }

    public void setAllowPhotosComments(int allowPhotosComments) {

        this.allowPhotosComments = allowPhotosComments;
    }

    public int getAllowPhotosComments() {

        return this.allowPhotosComments;
    }

    public void setAllowMessages(int allowMessages) {

        this.allowMessages = allowMessages;
    }

    public int getAllowMessages() {

        return this.allowMessages;
    }

    public void setAllowShowMyBirthday(int allowShowMyBirthday) {

        this.allowShowMyBirthday = allowShowMyBirthday;
    }

    public int getAllowShowMyBirthday() {

        return this.allowShowMyBirthday;
    }

    public void setLastActive(int lastAuthorize) {

        this.lastAuthorize = lastAuthorize;
    }

    public int getLastActive() {

        return this.lastAuthorize;
    }

    public void setDistance(double distance) {

        this.distance = distance;
    }

    public double getDistance() {

        return this.distance;
    }

    public void setLastActiveDate(String lastAuthorizeDate) {

        this.lastAuthorizeDate = lastAuthorizeDate;
    }

    public String getLastActiveDate() {

        return this.lastAuthorizeDate;
    }

    public void setCreateDate(String createDate) {

        this.createDate = createDate;
    }

    public String getCreateDate() {

        return this.createDate;
    }

    public String getBirthDate() {

        int tMonth = this.month + 1;

        return this.day + "/" + tMonth + "/" + this.year;
    }

    public void setLastActiveTimeAgo(String lastAuthorizeTimeAgo) {

        this.lastAuthorizeTimeAgo = lastAuthorizeTimeAgo;
    }

    public String getLastActiveTimeAgo() {

        return this.lastAuthorizeTimeAgo;
    }

    public void setBlocked(Boolean blocked) {

        this.blocked = blocked;
    }

    public Boolean isBlocked() {

        return this.blocked;
    }

    public void setFollower(Boolean follower) {

        this.follower = follower;
    }

    public Boolean isFollower() {

        return this.follower;
    }

    public void setFriend(Boolean friend) {

        this.friend = friend;
    }

    public Boolean isFriend() {

        return this.friend;
    }

    public void setFollow(Boolean follow) {

        this.follow = follow;
    }

    public Boolean isFollow() {

        return this.follow;
    }

    public void setOnline(Boolean online) {

        this.online = online;
    }

    public Boolean isOnline() {

        return this.online;
    }

    public void setMyLike(Boolean myLike) {

        this.myLike = myLike;
    }

    public Boolean isMyLike() {

        return this.myLike;
    }

    public void setInBlackList(Boolean inBlackList) {

        this.inBlackList = inBlackList;
    }

    public Boolean isInBlackList() {

        return this.inBlackList;
    }



































    public void addFollower() {

        CustomRequest jsonReq = new CustomRequest(Request.Method.POST, METHOD_PROFILE_FOLLOW, null,
                new Response.Listener<JSONObject>() {
                    @Override
                    public void onResponse(JSONObject response) {

                        try {

                            if (response.getBoolean("error") == false) {


                            }

                        } catch (JSONException e) {

                            e.printStackTrace();

                        } finally {

//                            Toast.makeText(getApplicationContext(), response.toString(), Toast.LENGTH_LONG).show();
                        }
                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {

//                     Toast.makeText(getApplicationContext(), error.getMessage(), Toast.LENGTH_LONG).show();
            }
        }) {

            @Override
            protected Map<String, String> getParams() {
                Map<String, String> params = new HashMap<String, String>();
                params.put("accountId", Long.toString(App.getInstance().getId()));
                params.put("accessToken", App.getInstance().getAccessToken());
                params.put("profileId", Long.toString(getId()));

                return params;
            }
        };

        App.getInstance().addToRequestQueue(jsonReq);
    }

    @Override
    public int describeContents() {

        return 0;
    }

    @Override
    public void writeToParcel(Parcel dest, int flags) {

    }
    public static final Parcelable.Creator CREATOR = new Parcelable.Creator() {

        public Profile createFromParcel(Parcel in) {

            return new Profile();
        }

        public Profile[] newArray(int size) {
            return new Profile[size];
        }
    };
}
