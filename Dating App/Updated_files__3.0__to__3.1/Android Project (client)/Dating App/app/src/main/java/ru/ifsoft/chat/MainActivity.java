package ru.ifsoft.chat;

import android.content.Intent;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentManager;
import android.support.v4.widget.DrawerLayout;
import android.support.v7.widget.Toolbar;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.LinearLayout;

import com.google.android.gms.ads.AdRequest;
import com.google.android.gms.ads.AdView;

import ru.ifsoft.chat.app.App;
import ru.ifsoft.chat.common.ActivityBase;
import ru.ifsoft.chat.dialogs.FriendRequestActionDialog;
import ru.ifsoft.chat.dialogs.ImageChooseDialog;
import ru.ifsoft.chat.dialogs.MyPhotoActionDialog;
import ru.ifsoft.chat.dialogs.PeopleNearbySettingsDialog;
import ru.ifsoft.chat.dialogs.PeopleNearbySexDialog;
import ru.ifsoft.chat.dialogs.PhotoDeleteDialog;
import ru.ifsoft.chat.dialogs.ProfileBlockDialog;
import ru.ifsoft.chat.dialogs.ProfileReportDialog;
import ru.ifsoft.chat.dialogs.SearchSettingsDialog;

public class MainActivity extends ActivityBase implements FragmentDrawer.FragmentDrawerListener, ImageChooseDialog.AlertPositiveListener, ProfileReportDialog.AlertPositiveListener, ProfileBlockDialog.AlertPositiveListener, PhotoDeleteDialog.AlertPositiveListener, MyPhotoActionDialog.AlertPositiveListener, FriendRequestActionDialog.AlertPositiveListener, SearchSettingsDialog.AlertPositiveListener, PeopleNearbySettingsDialog.AlertPositiveListener, PeopleNearbySexDialog.AlertPositiveListener {

    Toolbar mToolbar;

    private FragmentDrawer drawerFragment;

    // used to store app title
    private CharSequence mTitle;

    LinearLayout mContainerAdmob;

    Fragment fragment;
    Boolean action = false;
    int page = 0;

    private Boolean restore = false;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        if (savedInstanceState != null) {

            //Restore the fragment's instance
            fragment = getSupportFragmentManager().getFragment(savedInstanceState, "currentFragment");

            restore = savedInstanceState.getBoolean("restore");
            mTitle = savedInstanceState.getString("mTitle");

        } else {

            fragment = new Fragment();

            restore = false;
            mTitle = getString(R.string.app_name);
        }

        if (fragment != null) {

            FragmentManager fragmentManager = getSupportFragmentManager();
            fragmentManager.beginTransaction().replace(R.id.container_body, fragment).commit();
        }

        mToolbar = (Toolbar) findViewById(R.id.toolbar);

        setSupportActionBar(mToolbar);
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        getSupportActionBar().setHomeButtonEnabled(true);
        getSupportActionBar().setTitle(mTitle);

        drawerFragment = (FragmentDrawer) getFragmentManager().findFragmentById(R.id.fragment_navigation_drawer);
        drawerFragment.setUp(R.id.fragment_navigation_drawer, (DrawerLayout) findViewById(R.id.drawer_layout), mToolbar);
        drawerFragment.setDrawerListener(this);

        mContainerAdmob = (LinearLayout) findViewById(R.id.container_admob);

        if (App.getInstance().getAdmob() == ADMOB_ENABLED) {

            mContainerAdmob.setVisibility(View.VISIBLE);

            AdView mAdView = (AdView) findViewById(R.id.adView);
            AdRequest adRequest = new AdRequest.Builder().build();
            mAdView.loadAd(adRequest);
        }

        if (!restore) {

            displayView(4);
        }
    }

    @Override
    protected void onSaveInstanceState(Bundle outState) {

        super.onSaveInstanceState(outState);

        outState.putBoolean("restore", true);
        outState.putString("mTitle", getSupportActionBar().getTitle().toString());
        getSupportFragmentManager().putFragment(outState, "currentFragment", fragment);
    }

    @Override
    public void onActivityResult(int requestCode, int resultCode, Intent data) {

        super.onActivityResult(requestCode, resultCode, data);
    }

    @Override
    public void onRequestPermissionsResult(int requestCode, String permissions[], int[] grantResults) {

        super.onRequestPermissionsResult(requestCode, permissions, grantResults);

        fragment.onRequestPermissionsResult(requestCode, permissions, grantResults);
    }

    @Override
    public void onChangeDistance(int position) {

        PeopleNearbyFragment p = (PeopleNearbyFragment) fragment;
        p.onChangeDistance(position);
    }

    @Override
    public void onChangeSex(int position) {

        PeopleNearbyFragment p = (PeopleNearbyFragment) fragment;
        p.onChangeSex(position);
    }

    @Override
    public void onCloseSettingsDialog(int searchGender, int searchOnline, int searchPhoto, int searchProMode, int searchAgeFrom, int searchAgeTo) {

        SearchFragment p = (SearchFragment) fragment;
        p.onCloseSettingsDialog(searchGender, searchOnline, searchPhoto, searchProMode, searchAgeFrom, searchAgeTo);
    }

    @Override
    public void onAcceptRequest(int position) {

        NotificationsFragment p = (NotificationsFragment) fragment;
        p.onAcceptRequest(position);
    }

    @Override
    public void onRejectRequest(int position) {

        NotificationsFragment p = (NotificationsFragment) fragment;
        p.onRejectRequest(position);
    }

    @Override
    public void onPhotoDelete(int position) {

        GalleryFragment p = (GalleryFragment) fragment;
        p.onPhotoDelete(position);
    }

    @Override
    public void onPhotoRemoveDialog(int position) {

        GalleryFragment p = (GalleryFragment) fragment;
        p.onPhotoRemove(position);
    }

    @Override
    public void onImageFromGallery() {

        ProfileFragment p = (ProfileFragment) fragment;
        p.imageFromGallery();
    }

    @Override
    public void onImageFromCamera() {

        ProfileFragment p = (ProfileFragment) fragment;
        p.imageFromCamera();
    }

    @Override
    public void onProfileReport(int position) {

        ProfileFragment p = (ProfileFragment) fragment;
        p.onProfileReport(position);
    }

    @Override
    public void onProfileBlock() {

        ProfileFragment p = (ProfileFragment) fragment;
        p.onProfileBlock();
    }

    @Override
    public void onDrawerItemSelected(View view, int position) {

        displayView(position);
    }

    private void displayView(int position) {

        action = false;

        switch (position) {

            case 0: {

                break;
            }

            case 1: {

                page = 1;

                fragment = new ProfileFragment();
                getSupportActionBar().setTitle(R.string.page_1);

                action = true;

                break;
            }

            case 2: {

                page = 2;

                fragment = new GalleryFragment();
                getSupportActionBar().setTitle(R.string.page_2);

                action = true;

                break;
            }

            case 3: {

                page = 3;

                fragment = new FriendsFragment();
                getSupportActionBar().setTitle(R.string.page_3);
                action = true;

                break;
            }

            case 4: {

                page = 4;

                fragment = new DialogsFragment();
                getSupportActionBar().setTitle(R.string.page_4);

                action = true;

                break;
            }

            case 5: {

                page = 5;

                fragment = new NotificationsFragment();
                getSupportActionBar().setTitle(R.string.page_5);

                action = true;

                break;
            }

            case 6: {

                page = 6;

                fragment = new GuestsFragment();
                getSupportActionBar().setTitle(R.string.page_6);

                action = true;

                break;
            }

            case 7: {

                page = 7;

                fragment = new LikesFragment();
                getSupportActionBar().setTitle(R.string.page_7);

                action = true;

                break;
            }

            case 8: {

                page = 8;

                fragment = new LikedFragment();
                getSupportActionBar().setTitle(R.string.page_8);

                action = true;

                break;
            }

            case 9: {

                page = 9;

                // Upgrade

                fragment = new UpgradesFragment();
                getSupportActionBar().setTitle(R.string.page_9);

                action = true;

                break;
            }

            case 10: {

                page = 10;

                // People Nearby

                fragment = new PeopleNearbyFragment();
                getSupportActionBar().setTitle(R.string.page_10);

                action = true;

                break;
            }

            case 11: {

                page = 11;

                // Photos Stream

                fragment = new StreamFragment();
                getSupportActionBar().setTitle(R.string.page_14);

                action = true;

                break;
            }

            case 12: {

                page = 12;

                // Photos Stream

                fragment = new FeedFragment();
                getSupportActionBar().setTitle(R.string.page_15);

                action = true;

                break;
            }

            case 13: {

                page = 13;

                fragment = new SearchFragment();
                getSupportActionBar().setTitle("");

                action = true;

                break;
            }

            default: {

                Intent i = new Intent(MainActivity.this, SettingsActivity.class);
                startActivity(i);
            }
        }

        if (action) {

            FragmentManager fragmentManager = getSupportFragmentManager();
            fragmentManager.beginTransaction()
                    .replace(R.id.container_body, fragment)
                    .commit();
        }
    }

    public void hideAds() {

        if (App.getInstance().getAdmob() == ADMOB_DISABLED) {

            mContainerAdmob.setVisibility(View.GONE);
        }
    }

    @Override
    public boolean onPrepareOptionsMenu(Menu menu) {
        // If the nav drawer is open, hide action items related to the content view
        return super.onPrepareOptionsMenu(menu);
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {

        return super.onCreateOptionsMenu(menu);
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        // Handle action bar item clicks here. The action bar will
        // automatically handle clicks on the Home/Up button, so long
        // as you specify a parent activity in AndroidManifest.xml.
        switch (item.getItemId()) {

            case android.R.id.home: {

                return true;
            }

            default: {

                return super.onOptionsItemSelected(item);
            }
        }
    }

    @Override
    public void onBackPressed() {

        if (drawerFragment.isDrawerOpen()) {

            drawerFragment.closeDrawer();

        } else {

            super.onBackPressed();
        }
    }

    @Override
    public void setTitle(CharSequence title) {

        mTitle = title;
        getSupportActionBar().setTitle(mTitle);
    }
}
